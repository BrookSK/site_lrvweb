<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class ClientProjectController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        if (!$clientId) { $this->redirect('/login'); return ''; }

        $projects = Database::getInstance()->fetchAll("SELECT * FROM projects WHERE client_id = :cid AND deleted_at IS NULL ORDER BY created_at DESC", ['cid' => $clientId]);
        return $this->view('client/projects/index', ['title' => 'Meus Projetos', 'projects' => $projects, 'user' => $this->getUser()], 'client');
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $db = Database::getInstance();

        $project = $db->fetchOne("SELECT * FROM projects WHERE id = :id AND client_id = :cid AND deleted_at IS NULL", ['id' => (int) $params['id'], 'cid' => $clientId]);
        if (!$project) { $this->redirect('/cliente/projetos'); return ''; }

        $tasks = $db->fetchAll("SELECT * FROM project_tasks WHERE project_id = :id ORDER BY priority DESC", ['id' => $project['id']]);
        return $this->view('client/projects/show', ['title' => $project['name'], 'project' => $project, 'tasks' => $tasks, 'user' => $this->getUser()], 'client');
    }

    private function getClientId(): ?int
    {
        $user = $this->getUser();
        $client = Database::getInstance()->fetchOne("SELECT id FROM clients WHERE user_id = :uid", ['uid' => $user['id']]);
        return $client ? (int) $client['id'] : null;
    }
}
