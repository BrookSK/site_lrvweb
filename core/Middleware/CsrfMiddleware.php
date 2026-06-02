<?php

/**
 * Middleware de proteção CSRF
 * 
 * @package Core\Middleware
 */

declare(strict_types=1);

namespace Core\Middleware;

use Core\Request;
use Core\Session;

class CsrfMiddleware
{
    /**
     * Valida o token CSRF em requisições POST
     */
    public static function handle(Request $request, Session $session): void
    {
        $token = $request->input('_token') ?? $request->getHeader('x-csrf-token');

        if (!$token || $token !== $session->getCsrfToken()) {
            http_response_code(419);
            echo json_encode(['error' => 'Token CSRF inválido. Recarregue a página.']);
            exit;
        }
    }
}
