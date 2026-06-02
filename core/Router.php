<?php

/**
 * Sistema de Rotas
 * 
 * Gerencia o registro e despacho de rotas HTTP.
 * Suporta parâmetros dinâmicos, grupos, prefixos e middlewares.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class Router
{
    private array $routes = [];
    private array $groupStack = [];
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Registra rota GET
     */
    public function get(string $path, array|string $action, array $middlewares = []): self
    {
        return $this->addRoute('GET', $path, $action, $middlewares);
    }

    /**
     * Registra rota POST
     */
    public function post(string $path, array|string $action, array $middlewares = []): self
    {
        return $this->addRoute('POST', $path, $action, $middlewares);
    }

    /**
     * Registra rota PUT
     */
    public function put(string $path, array|string $action, array $middlewares = []): self
    {
        return $this->addRoute('PUT', $path, $action, $middlewares);
    }

    /**
     * Registra rota DELETE
     */
    public function delete(string $path, array|string $action, array $middlewares = []): self
    {
        return $this->addRoute('DELETE', $path, $action, $middlewares);
    }

    /**
     * Registra rota PATCH
     */
    public function patch(string $path, array|string $action, array $middlewares = []): self
    {
        return $this->addRoute('PATCH', $path, $action, $middlewares);
    }

    /**
     * Cria um grupo de rotas com prefixo e middlewares compartilhados
     */
    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    /**
     * Adiciona uma rota ao registro
     */
    private function addRoute(string $method, string $path, array|string $action, array $middlewares = []): self
    {
        $prefix = $this->getGroupPrefix();
        $groupMiddlewares = $this->getGroupMiddlewares();

        $fullPath = rtrim($prefix . '/' . ltrim($path, '/'), '/') ?: '/';

        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'action' => $action,
            'middlewares' => array_merge($groupMiddlewares, $middlewares),
            'pattern' => $this->buildPattern($fullPath),
        ];

        return $this;
    }

    /**
     * Despacha a requisição para o controller correto
     */
    public function dispatch(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $matches = [];
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Remove matches numéricos
                $params = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);

                // Executa middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareClass = "Core\\Middleware\\{$middleware}";
                    if (class_exists($middlewareClass)) {
                        $middlewareInstance = new $middlewareClass();
                        $result = $middlewareInstance->handle($this->request, $this->response);
                        if ($result === false) {
                            return;
                        }
                    }
                }

                // Executa a ação
                $this->executeAction($route['action'], $params);
                return;
            }
        }

        // 404 - Rota não encontrada
        $this->response->setStatusCode(404);
        $this->response->setBody(View::render('errors/404'));
        $this->response->send();
    }

    /**
     * Executa a ação do controller
     */
    private function executeAction(array|string $action, array $params): void
    {
        if (is_string($action)) {
            // Closure ou função
            call_user_func($action, ...$params);
            return;
        }

        [$controllerClass, $method] = $action;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller não encontrado: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Método não encontrado: {$controllerClass}::{$method}");
        }

        $result = $controller->$method($this->request, $this->response, $params);

        if (is_string($result)) {
            $this->response->setBody($result);
            $this->response->send();
        }
    }

    /**
     * Constrói o padrão regex para a rota
     */
    private function buildPattern(string $path): string
    {
        // Converte parâmetros {param} para regex named groups
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        // Converte parâmetros opcionais {param?}
        $pattern = preg_replace('/\{([a-zA-Z_]+)\?\}/', '(?P<$1>[^/]*)?', $pattern);

        return '#^' . $pattern . '$#';
    }

    /**
     * Obtém o prefixo do grupo atual
     */
    private function getGroupPrefix(): string
    {
        $prefix = '';
        foreach ($this->groupStack as $group) {
            $prefix .= $group['prefix'] ?? '';
        }
        return $prefix;
    }

    /**
     * Obtém os middlewares do grupo atual
     */
    private function getGroupMiddlewares(): array
    {
        $middlewares = [];
        foreach ($this->groupStack as $group) {
            $middlewares = array_merge($middlewares, $group['middlewares'] ?? []);
        }
        return $middlewares;
    }
}
