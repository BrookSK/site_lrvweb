<?php

/**
 * Classe de Requisição HTTP
 * 
 * Encapsula dados da requisição HTTP de forma segura.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;
    private array $cookies;
    private array $headers;
    private ?string $body = null;

    public function __construct()
    {
        $this->get = $this->sanitize($_GET);
        $this->post = $this->sanitize($_POST);
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->headers = $this->parseHeaders();
    }

    /**
     * Retorna o método HTTP
     */
    public function getMethod(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';

        // Suporte a method override via POST
        if ($method === 'POST') {
            $override = $this->post['_method'] ?? $this->getHeader('X-HTTP-Method-Override');
            if ($override) {
                $method = strtoupper($override);
            }
        }

        return $method;
    }

    /**
     * Retorna a URI limpa
     */
    public function getUri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        $uri = rtrim($uri, '/') ?: '/';

        return $uri;
    }

    /**
     * Retorna parâmetro GET
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Retorna parâmetro POST sem sanitização (para conteúdo HTML)
     */
    public function raw(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Retorna parâmetro POST
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->getJsonInput($key) ?? $default;
    }

    /**
     * Retorna todos os inputs (GET + POST + JSON)
     */
    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->getJsonBody() ?? []);
    }

    /**
     * Verifica se um input existe
     */
    public function has(string $key): bool
    {
        return isset($this->get[$key]) || isset($this->post[$key]);
    }

    /**
     * Retorna arquivo enviado
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Retorna um header
     */
    public function getHeader(string $name): ?string
    {
        $name = strtolower($name);
        return $this->headers[$name] ?? null;
    }

    /**
     * Retorna o IP do cliente
     */
    public function getIp(): string
    {
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'REMOTE_ADDR'];

        foreach ($keys as $key) {
            if (!empty($this->server[$key])) {
                $ip = explode(',', $this->server[$key])[0];
                if (filter_var(trim($ip), FILTER_VALIDATE_IP)) {
                    return trim($ip);
                }
            }
        }

        return '127.0.0.1';
    }

    /**
     * Retorna o User Agent
     */
    public function getUserAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Verifica se é requisição AJAX
     */
    public function isAjax(): bool
    {
        return $this->getHeader('x-requested-with') === 'XMLHttpRequest';
    }

    /**
     * Verifica se é requisição JSON
     */
    public function isJson(): bool
    {
        return str_contains($this->getHeader('content-type') ?? '', 'application/json');
    }

    /**
     * Retorna o corpo raw da requisição
     */
    public function getRawBody(): string
    {
        if ($this->body === null) {
            $this->body = file_get_contents('php://input') ?: '';
        }
        return $this->body;
    }

    /**
     * Retorna corpo JSON decodificado
     */
    private function getJsonBody(): ?array
    {
        if ($this->isJson()) {
            $data = json_decode($this->getRawBody(), true);
            return is_array($data) ? $data : null;
        }
        return null;
    }

    /**
     * Retorna valor específico do JSON body
     */
    private function getJsonInput(string $key): mixed
    {
        $json = $this->getJsonBody();
        return $json[$key] ?? null;
    }

    /**
     * Sanitiza inputs contra XSS
     */
    private function sanitize(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
            }
        }
        return $sanitized;
    }

    /**
     * Extrai headers da requisição
     */
    private function parseHeaders(): array
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$name] = $value;
            }
        }

        if (isset($this->server['CONTENT_TYPE'])) {
            $headers['content-type'] = $this->server['CONTENT_TYPE'];
        }

        return $headers;
    }
}
