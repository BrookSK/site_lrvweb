<?php

/**
 * Middleware de Administração
 * 
 * Verifica se o usuário tem acesso administrativo.
 * 
 * @package Core\Middleware
 */

declare(strict_types=1);

namespace Core\Middleware;

use Core\Request;
use Core\Response;
use Core\Session;

class AdminMiddleware
{
    private array $adminRoles = ['super_admin', 'socio', 'desenvolvedor', 'suporte', 'comercial', 'financeiro'];

    /**
     * Verifica acesso administrativo
     */
    public function handle(Request $request, Response $response): bool
    {
        $session = Session::getInstance();
        $user = $session->get('user');

        if (!$user || !in_array($user['role'] ?? '', $this->adminRoles)) {
            if ($request->isAjax() || $request->isJson()) {
                $response->error('Acesso negado', 403);
            } else {
                $response->redirect('/login');
            }
            return false;
        }

        return true;
    }
}
