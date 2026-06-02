<?php

/**
 * Classe principal da aplicação
 * 
 * Responsável por inicializar todos os componentes,
 * processar a requisição e despachar a resposta.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class Application
{
    private Router $router;
    private Request $request;
    private Response $response;
    private Session $session;
    private static ?Application $instance = null;

    public function __construct()
    {
        self::$instance = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);

        // Registra rotas
        $this->registerRoutes();
    }

    /**
     * Retorna a instância singleton da aplicação
     */
    public static function getInstance(): ?Application
    {
        return self::$instance;
    }

    /**
     * Executa a aplicação
     */
    public function run(): void
    {
        try {
            // Inicia sessão
            $this->session->start();

            // Aplica middlewares globais
            $this->applyGlobalMiddlewares();

            // Despacha a rota
            $this->router->dispatch();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * Registra todas as rotas da aplicação
     */
    private function registerRoutes(): void
    {
        $routeFiles = [
            ROOT_PATH . '/routes/web.php',
            ROOT_PATH . '/routes/admin.php',
            ROOT_PATH . '/routes/api.php',
            ROOT_PATH . '/routes/client.php',
        ];

        foreach ($routeFiles as $file) {
            if (file_exists($file)) {
                $router = $this->router;
                require $file;
            }
        }
    }

    /**
     * Aplica middlewares globais
     */
    private function applyGlobalMiddlewares(): void
    {
        // CSRF Protection
        if ($this->request->getMethod() === 'POST' && Config::get('security.csrf_enabled', true)) {
            Middleware\CsrfMiddleware::handle($this->request, $this->session);
        }

        // Rate Limiting
        Middleware\RateLimitMiddleware::handle($this->request);
    }

    /**
     * Trata exceções não capturadas
     */
    private function handleException(\Throwable $e): void
    {
        // Loga o erro
        Logger::error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        if (APP_DEBUG) {
            $this->response->setStatusCode(500);
            $this->response->setBody($this->renderDebugError($e));
        } else {
            $this->response->setStatusCode(500);
            $this->response->setBody(View::render('errors/500'));
        }

        $this->response->send();
    }

    /**
     * Renderiza erro em modo debug
     */
    private function renderDebugError(\Throwable $e): string
    {
        return sprintf(
            '<h1>Error: %s</h1><p>File: %s:%d</p><pre>%s</pre>',
            htmlspecialchars($e->getMessage()),
            htmlspecialchars($e->getFile()),
            $e->getLine(),
            htmlspecialchars($e->getTraceAsString())
        );
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getSession(): Session
    {
        return $this->session;
    }
}
