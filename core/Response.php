<?php

/**
 * Classe de Resposta HTTP
 * 
 * Gerencia a resposta HTTP enviada ao cliente.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';

    /**
     * Define o código de status HTTP
     */
    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Define um header
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Define o corpo da resposta
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Envia a resposta ao cliente
     */
    public function send(): void
    {
        // Status code
        http_response_code($this->statusCode);

        // Headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Body
        echo $this->body;
        exit;
    }

    /**
     * Redireciona para outra URL
     */
    public function redirect(string $url, int $code = 302): void
    {
        $this->setStatusCode($code);
        $this->setHeader('Location', $url);
        $this->send();
    }

    /**
     * Resposta JSON
     */
    public function json(mixed $data, int $statusCode = 200): void
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->setBody(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->send();
    }

    /**
     * Resposta de erro JSON
     */
    public function error(string $message, int $statusCode = 400, array $errors = []): void
    {
        $data = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $data['errors'] = $errors;
        }

        $this->json($data, $statusCode);
    }

    /**
     * Resposta de sucesso JSON
     */
    public function success(mixed $data = null, string $message = 'OK', int $statusCode = 200): void
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        $this->json($response, $statusCode);
    }

    /**
     * Download de arquivo
     */
    public function download(string $filePath, ?string $fileName = null): void
    {
        if (!file_exists($filePath)) {
            $this->error('Arquivo não encontrado', 404);
            return;
        }

        $fileName = $fileName ?? basename($filePath);

        $this->setHeader('Content-Type', 'application/octet-stream');
        $this->setHeader('Content-Disposition', "attachment; filename=\"{$fileName}\"");
        $this->setHeader('Content-Length', (string) filesize($filePath));

        http_response_code(200);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        readfile($filePath);
        exit;
    }
}
