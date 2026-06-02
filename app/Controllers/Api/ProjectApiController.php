<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class ProjectApiController extends Controller
{
    public function index(Request $request, Response $response): void
    {
        $this->requireAuth();
        $projects = Database::getInstance()->fetchAll("SELECT p.*, c.name as client_name FROM projects p LEFT JOIN clients c ON p.client_id = c.id WHERE p.deleted_at IS NULL ORDER BY p.created_at DESC");
        $this->response->success($projects);
    }

    public function show(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $project = Database::getInstance()->fetchOne("SELECT * FROM projects WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$project) { $this->response->error('Não encontrado', 404); return; }
        $this->response->success($project);
    }

    public function updateStatus(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $id = (int) $params['id'];
        $status = $request->input('status');
        Database::getInstance()->update('projects', ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
        $this->response->success(null, 'Status atualizado');
    }
}
