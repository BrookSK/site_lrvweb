<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class ProjectController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('projects.view');
        $db = Database::getInstance();

        $projects = $db->fetchAll("
            SELECT p.*, c.name as client_name
            FROM projects p
            LEFT JOIN clients c ON p.client_id = c.id
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC
        ");

        return $this->adminView('projects/index', ['title' => 'Projetos', 'projects' => $projects]);
    }

    public function kanban(Request $request, Response $response): string
    {
        $this->requirePermission('projects.view');
        $db = Database::getInstance();

        $projects = $db->fetchAll("
            SELECT p.*, c.name as client_name
            FROM projects p
            LEFT JOIN clients c ON p.client_id = c.id
            WHERE p.deleted_at IS NULL
            ORDER BY p.updated_at DESC
        ");

        $statuses = [
            'planning' => 'Planejamento',
            'development' => 'Desenvolvimento',
            'testing' => 'Testes',
            'review' => 'Revisão',
            'completed' => 'Concluído',
        ];

        $grouped = [];
        foreach ($statuses as $key => $label) {
            $grouped[$key] = ['label' => $label, 'projects' => []];
        }
        foreach ($projects as $p) {
            if (isset($grouped[$p['status']])) {
                $grouped[$p['status']]['projects'][] = $p;
            }
        }

        return $this->adminView('projects/kanban', ['title' => 'Projetos - Kanban', 'statuses' => $grouped]);
    }

    public function create(Request $request, Response $response): string
    {
        $this->requirePermission('projects.create');
        $db = Database::getInstance();
        $clients = $db->fetchAll("SELECT id, name, company FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        $users = $db->fetchAll("SELECT id, name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");

        return $this->adminView('projects/form', ['title' => 'Novo Projeto', 'project' => null, 'clients' => $clients, 'users' => $users]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('projects.create');
        $data = $this->validate(['name' => 'required|max:200', 'client_id' => 'required|integer']);

        $db = Database::getInstance();
        $id = $db->insert('projects', [
            'client_id' => $data['client_id'],
            'name' => $data['name'],
            'description' => $request->input('description'),
            'status' => 'planning',
            'value' => $request->input('value') ?: null,
            'start_date' => $request->input('start_date') ?: null,
            'due_date' => $request->input('due_date') ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Adiciona membros
        $members = $request->input('members') ?? [];
        if (is_array($members)) {
            foreach ($members as $userId) {
                $db->insert('project_members', ['project_id' => $id, 'user_id' => (int) $userId, 'role' => 'member']);
            }
        }

        Logger::audit('Projeto criado', ['project_id' => $id]);
        $this->session->flash('success', 'Projeto criado!');
        $this->redirect("/admin/projetos/{$id}");
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('projects.view');
        $db = Database::getInstance();
        $id = (int) $params['id'];

        $project = $db->fetchOne("SELECT p.*, c.name as client_name FROM projects p LEFT JOIN clients c ON p.client_id = c.id WHERE p.id = :id AND p.deleted_at IS NULL", ['id' => $id]);
        if (!$project) { $this->redirect('/admin/projetos'); return ''; }

        $tasks = $db->fetchAll("SELECT t.*, u.name as assigned_name FROM project_tasks t LEFT JOIN users u ON t.assigned_to = u.id WHERE t.project_id = :id ORDER BY t.priority DESC, t.created_at", ['id' => $id]);
        $members = $db->fetchAll("SELECT u.id, u.name, u.avatar, pm.role FROM project_members pm JOIN users u ON pm.user_id = u.id WHERE pm.project_id = :id", ['id' => $id]);
        $documents = $db->fetchAll("SELECT * FROM documents WHERE project_id = :id AND deleted_at IS NULL", ['id' => $id]);

        return $this->adminView('projects/show', ['title' => $project['name'], 'project' => $project, 'tasks' => $tasks, 'members' => $members, 'documents' => $documents]);
    }

    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('projects.edit');
        $db = Database::getInstance();
        $project = $db->fetchOne("SELECT * FROM projects WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$project) { $this->redirect('/admin/projetos'); return ''; }

        $clients = $db->fetchAll("SELECT id, name FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        $users = $db->fetchAll("SELECT id, name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");

        return $this->adminView('projects/form', ['title' => 'Editar: ' . $project['name'], 'project' => $project, 'clients' => $clients, 'users' => $users]);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('projects.edit');
        $id = (int) $params['id'];

        $db = Database::getInstance();
        $db->update('projects', [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'value' => $request->input('value') ?: null,
            'start_date' => $request->input('start_date') ?: null,
            'due_date' => $request->input('due_date') ?: null,
            'completed_at' => $request->input('status') === 'completed' ? date('Y-m-d H:i:s') : null,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);

        // Adiciona/atualiza membros
        $members = $request->input('members') ?? [];
        if (is_array($members)) {
            // Remove membros antigos e adiciona novos
            $db->delete('project_members', 'project_id = :pid', ['pid' => $id]);
            foreach ($members as $userId) {
                $db->insert('project_members', ['project_id' => $id, 'user_id' => (int) $userId, 'role' => 'member']);
            }
        }

        Logger::audit('Projeto atualizado', ['project_id' => $id]);
        $this->session->flash('success', 'Projeto atualizado!');
        $this->redirect("/admin/projetos/{$id}");
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('projects.delete');
        Database::getInstance()->update('projects', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Projeto excluído!');
        $this->redirect('/admin/projetos');
    }

    public function addTask(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('projects.edit');
        $projectId = (int) $params['id'];

        $db = Database::getInstance();
        $db->insert('project_tasks', [
            'project_id' => $projectId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'assigned_to' => $request->input('assigned_to') ?: null,
            'priority' => $request->input('priority') ?? 'medium',
            'due_date' => $request->input('due_date') ?: null,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($request->isAjax()) { $this->response->success(null, 'Tarefa criada!'); }
        else { $this->session->flash('success', 'Tarefa criada!'); $this->redirect("/admin/projetos/{$projectId}"); }
    }

    public function updateTask(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('projects.edit');
        $taskId = (int) $params['id'];
        $db = Database::getInstance();

        $db->update('project_tasks', [
            'status' => $request->input('status'),
            'completed_at' => $request->input('status') === 'completed' ? date('Y-m-d H:i:s') : null,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $taskId]);

        $this->response->success(null, 'Tarefa atualizada!');
    }
}
