<?php

/**
 * Middleware de Autenticação
 * 
 * Verifica se o usuário está autenticado.
 * 
 * @package Core\Middleware
 */

declare(strict_types=1);

namespace Core\Middleware;

use Core\Request;
use Core\Response;
use Core\Session;

class AuthMiddleware
{
    /**
     * Verifica autenticação
     */
    public function handle(Request $request, Response $response): bool
    {
        $session = Session::getInstance();

        if (!$session->has('user')) {
            if ($request->isAjax() || $request->isJson()) {
                $response->error('Não autorizado', 401);
            } else {
                $response->redirect('/login');
            }
            return false;
        }

        return true;
    }
}
