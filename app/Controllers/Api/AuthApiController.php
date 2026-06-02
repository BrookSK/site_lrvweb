<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class AuthApiController extends Controller
{
    public function login(Request $request, Response $response): void
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (!$email || !$password) {
            $this->response->error('E-mail e senha são obrigatórios', 422);
            return;
        }

        $db = Database::getInstance();
        $user = $db->fetchOne("SELECT u.*, r.name as role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = :email AND u.is_active = 1", ['email' => $email]);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->response->error('Credenciais inválidas', 401);
            return;
        }

        // Gera token simples (em produção usar JWT)
        $token = bin2hex(random_bytes(32));
        $db->update('users', ['remember_token' => $token], 'id = :id', ['id' => $user['id']]);

        $this->response->success([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ],
        ], 'Login realizado');
    }

    public function refresh(Request $request, Response $response): void
    {
        // TODO: Implementar refresh token
        $this->response->success(null, 'Token válido');
    }
}
