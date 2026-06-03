<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class TeamController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('team.view');
        $users = Database::getInstance()->fetchAll("
            SELECT u.*, r.display_name as role_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.deleted_at IS NULL AND r.name != 'cliente'
            ORDER BY u.name
        ");

        return $this->adminView('team/index', ['title' => 'Equipe', 'users' => $users]);
    }

    public function create(Request $request, Response $response): string
    {
        $this->requirePermission('team.manage');
        $roles = Database::getInstance()->fetchAll("SELECT * FROM roles ORDER BY id");
        return $this->adminView('team/form', ['title' => 'Novo Membro', 'member' => null, 'roles' => $roles]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('team.manage');
        $data = $this->validate(['name' => 'required|max:150', 'email' => 'required|email|unique:users,email', 'password' => 'required|min:8', 'role_id' => 'required|integer']);

        $db = Database::getInstance();
        $id = $db->insert('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_ARGON2ID),
            'role_id' => (int) $data['role_id'],
            'phone' => $request->input('phone'),
            'position' => $request->input('position'),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Logger::audit('Membro criado', ['user_id' => $id]);
        $this->session->flash('success', 'Membro adicionado!');
        $this->redirect('/admin/equipe');
    }

    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('team.manage');
        $db = Database::getInstance();
        $member = $db->fetchOne("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$member) { $this->redirect('/admin/equipe'); return ''; }
        $roles = $db->fetchAll("SELECT * FROM roles ORDER BY id");

        return $this->adminView('team/form', ['title' => 'Editar: ' . $member['name'], 'member' => $member, 'roles' => $roles]);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('team.manage');
        $id = (int) $params['id'];
        $db = Database::getInstance();

        $updateData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => (int) $request->input('role_id'),
            'phone' => $request->input('phone'),
            'position' => $request->input('position'),
            'is_active' => (int) ($request->input('is_active') ?? 1),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $password = $request->input('password');
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_ARGON2ID);
        }

        $db->update('users', $updateData, 'id = :id', ['id' => $id]);
        Logger::audit('Membro atualizado', ['user_id' => $id]);
        $this->session->flash('success', 'Membro atualizado!');
        $this->redirect('/admin/equipe');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('team.manage');
        $id = (int) $params['id'];

        if ($id === ($this->getUser()['id'] ?? 0)) {
            $this->session->flash('error', 'Você não pode excluir a si mesmo.');
            $this->redirect('/admin/equipe');
            return;
        }

        Database::getInstance()->update('users', ['deleted_at' => date('Y-m-d H:i:s'), 'is_active' => 0], 'id = :id', ['id' => $id]);
        Logger::audit('Membro excluído', ['user_id' => $id]);
        $this->session->flash('success', 'Membro removido!');
        $this->redirect('/admin/equipe');
    }

    public function updatePermissions(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('team.manage');
        $userId = (int) $params['id'];
        $db = Database::getInstance();

        $user = $db->fetchOne("SELECT role_id FROM users WHERE id = :id", ['id' => $userId]);
        if (!$user) { $this->response->error('Usuário não encontrado', 404); return; }

        // Limpa permissões antigas do role e adiciona novas
        $permissions = $request->input('permissions') ?? [];
        $db->delete('role_permissions', 'role_id = :role_id', ['role_id' => $user['role_id']]);

        foreach ($permissions as $permId) {
            $db->insert('role_permissions', ['role_id' => $user['role_id'], 'permission_id' => (int) $permId]);
        }

        $this->session->flash('success', 'Permissões atualizadas!');
        $this->redirect('/admin/equipe');
    }
}
