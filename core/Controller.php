<?php

/**
 * Controller Base
 * 
 * Classe abstrata base para todos os controllers.
 * Fornece métodos utilitários comuns.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected Session $session;

    public function __construct()
    {
        $app = Application::getInstance();
        $this->request = $app->getRequest();
        $this->response = $app->getResponse();
        $this->session = $app->getSession();
    }

    /**
     * Renderiza uma view
     */
    protected function view(string $view, array $data = [], ?string $layout = 'default'): string
    {
        return View::render($view, $data, $layout);
    }

    /**
     * Renderiza view do painel admin
     */
    protected function adminView(string $view, array $data = []): string
    {
        $data['user'] = $this->session->get('user');
        return View::render("admin/{$view}", $data, 'admin');
    }

    /**
     * Redireciona
     */
    protected function redirect(string $url, int $code = 302): void
    {
        $this->response->redirect($url, $code);
    }

    /**
     * Retorna resposta JSON
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        $this->response->json($data, $statusCode);
    }

    /**
     * Valida inputs da requisição
     */
    protected function validate(array $rules): array
    {
        $validator = new Validator($this->request->all(), $rules);

        if (!$validator->passes()) {
            if ($this->request->isAjax() || $this->request->isJson()) {
                $this->response->error('Erro de validação', 422, $validator->getErrors());
            }

            $this->session->flash('errors', $validator->getErrors());
            $this->session->flash('old', $this->request->all());
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        return $validator->getValidData();
    }

    /**
     * Verifica se o usuário está autenticado
     */
    protected function isAuthenticated(): bool
    {
        return $this->session->has('user');
    }

    /**
     * Retorna o usuário autenticado
     */
    protected function getUser(): ?array
    {
        return $this->session->get('user');
    }

    /**
     * Verifica permissão do usuário
     */
    protected function hasPermission(string $permission): bool
    {
        $user = $this->getUser();
        if (!$user) {
            return false;
        }

        $permissions = $user['permissions'] ?? [];
        return in_array($permission, $permissions) || in_array('*', $permissions);
    }

    /**
     * Exige autenticação
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }
    }

    /**
     * Exige permissão específica
     */
    protected function requirePermission(string $permission): void
    {
        $this->requireAuth();

        if (!$this->hasPermission($permission)) {
            $this->response->setStatusCode(403);
            $this->response->setBody(View::render('errors/403'));
            $this->response->send();
        }
    }
}
