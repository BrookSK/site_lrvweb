<?php

/**
 * Sistema de Views/Templates
 * 
 * Renderiza templates PHP com suporte a layouts e componentes.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class View
{
    private static string $viewsPath = ROOT_PATH . '/resources/views/';
    private static array $shared = [];

    /**
     * Renderiza uma view com layout opcional
     */
    public static function render(string $view, array $data = [], ?string $layout = null): string
    {
        $data = array_merge(self::$shared, $data);
        $viewContent = self::renderFile($view, $data);

        if ($layout) {
            $data['content'] = $viewContent;
            return self::renderFile("layouts/{$layout}", $data);
        }

        return $viewContent;
    }

    /**
     * Renderiza um arquivo de view
     */
    private static function renderFile(string $view, array $data): string
    {
        $filePath = self::$viewsPath . str_replace('.', '/', $view) . '.php';

        if (!file_exists($filePath)) {
            throw new \RuntimeException("View não encontrada: {$view} ({$filePath})");
        }

        extract($data);
        ob_start();
        require $filePath;
        return ob_get_clean();
    }

    /**
     * Renderiza um componente/partial
     */
    public static function component(string $name, array $data = []): string
    {
        return self::renderFile("components/{$name}", array_merge(self::$shared, $data));
    }

    /**
     * Compartilha dados com todas as views
     */
    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Gera token CSRF
     */
    public static function csrf(): string
    {
        $token = Session::getInstance()->get('csrf_token', '');
        return '<input type="hidden" name="_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Gera campo de método HTTP
     */
    public static function method(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }

    /**
     * Escape HTML
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Helper para assets com cache buster
     */
    public static function asset(string $path): string
    {
        $baseUrl = Config::get('app.url', '');
        $filePath = ROOT_PATH . '/public/assets/' . ltrim($path, '/');
        $version = file_exists($filePath) ? filemtime($filePath) : time();
        return rtrim($baseUrl, '/') . '/assets/' . ltrim($path, '/') . '?v=' . $version;
    }

    /**
     * Helper para URL
     */
    public static function url(string $path = ''): string
    {
        $baseUrl = Config::get('app.url', '');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}
