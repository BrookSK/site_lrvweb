<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class ClientApiController extends Controller
{
    public function index(Request $request, Response $response): void
    {
        $this->requireAuth();
        $clients = Database::getInstance()->fetchAll("SELECT id, name, company, email, phone, is_active, created_at FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $this->response->success($clients);
    }

    public function show(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $client = Database::getInstance()->fetchOne("SELECT * FROM clients WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$client) { $this->response->error('Não encontrado', 404); return; }
        $this->response->success($client);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requireAuth();
        $data = $this->validate(['name' => 'required', 'email' => 'required|email']);
        $id = Database::getInstance()->insert('clients', array_merge($data, ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]));
        $this->response->success(['id' => $id], 'Criado', 201);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $id = (int) $params['id'];
        Database::getInstance()->update('clients', array_merge($request->all(), ['updated_at' => date('Y-m-d H:i:s')]), 'id = :id', ['id' => $id]);
        $this->response->success(null, 'Atualizado');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        Database::getInstance()->update('clients', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => (int) $params['id']]);
        $this->response->success(null, 'Excluído');
    }
}
