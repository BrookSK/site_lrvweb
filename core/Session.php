<?php

/**
 * Gerenciador de Sessões
 * 
 * Controla sessões PHP com segurança aprimorada.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

use Core\Config;

class Session
{
    private static ?Session $instance = null;
    private bool $started = false;

    /**
     * Retorna instância singleton
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inicia a sessão com configurações de segurança
     */
    public function start(): void
    {
        if ($this->started || session_status() === PHP_SESSION_ACTIVE) {
            $this->started = true;
            return;
        }

        $lifetime = (int) Config::get('session.lifetime', 120) * 60;
        $secure = (bool) Config::get('session.secure', false);

        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_name(Config::get('session.name', 'lrvweb_session'));
        session_start();

        // Regenerar ID periodicamente para prevenir session fixation
        if (!$this->has('_session_created')) {
            $this->set('_session_created', time());
        } elseif (time() - $this->get('_session_created') > 1800) {
            $this->regenerate();
        }

        // Gera CSRF token se não existir
        if (!$this->has('csrf_token')) {
            $this->set('csrf_token', bin2hex(random_bytes(32)));
        }

        $this->started = true;
        self::$instance = $this;
    }

    /**
     * Define um valor na sessão
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Obtém um valor da sessão
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verifica se existe
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove um valor
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Flash message (disponível apenas na próxima requisição)
     */
    public function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Obtém flash message
     */
    public function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /**
     * Verifica se existe flash
     */
    public function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    /**
     * Regenera o ID da sessão
     */
    public function regenerate(): void
    {
        session_regenerate_id(true);
        $this->set('_session_created', time());
    }

    /**
     * Destrói a sessão
     */
    public function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->started = false;
    }

    /**
     * Retorna o token CSRF
     */
    public function getCsrfToken(): string
    {
        return $this->get('csrf_token', '');
    }
}
