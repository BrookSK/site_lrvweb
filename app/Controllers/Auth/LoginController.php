<?php

/**
 * Controller de Autenticação
 * 
 * Gerencia login unificado, logout e recuperação de senha.
 * O sistema identifica automaticamente o tipo de usuário.
 * 
 * @package App\Controllers\Auth
 */

declare(strict_types=1);

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;
use Core\View;

class LoginController extends Controller
{
    /**
     * Exibe formulário de login
     */
    public function showLoginForm(Request $request, Response $response): string
    {
        if ($this->isAuthenticated()) {
            $this->redirectByRole();
        }

        return $this->view('auth/login', [
            'title' => 'Login',
        ], 'auth');
    }

    /**
     * Processa o login
     */
    public function login(Request $request, Response $response): void
    {
        $data = $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $db = Database::getInstance();

        // Busca usuário com role
        $user = $db->fetchOne("
            SELECT u.*, r.name as role, r.display_name as role_display
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.email = :email AND u.is_active = 1 AND u.deleted_at IS NULL
            LIMIT 1
        ", ['email' => $data['email']]);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Logger::warning('Tentativa de login falhou', [
                'email' => $data['email'],
                'ip' => $request->getIp(),
            ]);

            $this->session->flash('error', 'E-mail ou senha incorretos.');
            $this->redirect('/login');
            return;
        }

        // Busca permissões do usuário
        $permissions = $db->fetchAll("
            SELECT p.name 
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = :role_id
        ", ['role_id' => $user['role_id']]);

        $permissionNames = array_column($permissions, 'name');

        // Se for super_admin, tem todas as permissões
        if ($user['role'] === 'super_admin') {
            $permissionNames = ['*'];
        }

        // Armazena dados na sessão
        $this->session->set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'role_display' => $user['role_display'],
            'avatar' => $user['avatar'],
            'permissions' => $permissionNames,
        ]);

        // Regenera sessão por segurança
        $this->session->regenerate();

        // Atualiza último login
        $db->update('users', [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $request->getIp(),
        ], 'id = :id', ['id' => $user['id']]);

        Logger::audit('Login realizado', [
            'user_id' => $user['id'],
            'role' => $user['role'],
        ]);

        $this->redirectByRole();
    }

    /**
     * Logout
     */
    public function logout(Request $request, Response $response): void
    {
        $userId = $this->session->get('user')['id'] ?? null;

        if ($userId) {
            Logger::audit('Logout realizado', ['user_id' => $userId]);
        }

        $this->session->destroy();
        $this->redirect('/login');
    }

    /**
     * Exibe formulário de recuperação de senha
     */
    public function showForgotPassword(Request $request, Response $response): string
    {
        return $this->view('auth/forgot-password', [
            'title' => 'Recuperar Senha',
        ], 'auth');
    }

    /**
     * Processa recuperação de senha
     */
    public function forgotPassword(Request $request, Response $response): void
    {
        $data = $this->validate(['email' => 'required|email']);

        $db = Database::getInstance();
        $user = $db->fetchOne("SELECT id, name, email FROM users WHERE email = :email AND is_active = 1", [
            'email' => $data['email'],
        ]);

        // Sempre mostra mensagem de sucesso (segurança)
        $this->session->flash('success', 'Se o e-mail estiver cadastrado, você receberá as instruções de recuperação.');

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $db->update('users', [
                'password_reset_token' => $token,
                'password_reset_expires' => $expires,
            ], 'id = :id', ['id' => $user['id']]);

            // TODO: Enviar e-mail com link de recuperação
            Logger::info('Token de recuperação gerado', ['user_id' => $user['id']]);
        }

        $this->redirect('/recuperar-senha');
    }

    /**
     * Exibe formulário de redefinição de senha
     */
    public function showResetPassword(Request $request, Response $response, array $params): string
    {
        $token = $params['token'] ?? '';

        $db = Database::getInstance();
        $user = $db->fetchOne("
            SELECT id FROM users 
            WHERE password_reset_token = :token 
            AND password_reset_expires > NOW()
        ", ['token' => $token]);

        if (!$user) {
            $this->session->flash('error', 'Link inválido ou expirado.');
            $this->redirect('/login');
        }

        return $this->view('auth/reset-password', [
            'title' => 'Redefinir Senha',
            'token' => $token,
        ], 'auth');
    }

    /**
     * Processa redefinição de senha
     */
    public function resetPassword(Request $request, Response $response): void
    {
        $data = $this->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $db = Database::getInstance();
        $user = $db->fetchOne("
            SELECT id FROM users 
            WHERE password_reset_token = :token 
            AND password_reset_expires > NOW()
        ", ['token' => $data['token']]);

        if (!$user) {
            $this->session->flash('error', 'Link inválido ou expirado.');
            $this->redirect('/login');
            return;
        }

        $db->update('users', [
            'password' => password_hash($data['password'], PASSWORD_ARGON2ID),
            'password_reset_token' => null,
            'password_reset_expires' => null,
        ], 'id = :id', ['id' => $user['id']]);

        Logger::audit('Senha redefinida', ['user_id' => $user['id']]);

        $this->session->flash('success', 'Senha redefinida com sucesso! Faça login.');
        $this->redirect('/login');
    }

    /**
     * Redireciona baseado no role do usuário
     */
    private function redirectByRole(): void
    {
        $user = $this->session->get('user');
        $role = $user['role'] ?? '';

        if ($role === 'cliente') {
            $this->redirect('/cliente/dashboard');
        } else {
            $this->redirect('/admin/dashboard');
        }
    }
}
