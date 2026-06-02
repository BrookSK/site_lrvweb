<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class ServiceController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('settings.manage');
        $services = Database::getInstance()->fetchAll("SELECT * FROM services ORDER BY sort_order, name");
        return $this->adminView('services/index', ['title' => 'Serviços', 'services' => $services]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('settings.manage');
        $data = $this->validate(['name' => 'required|max:200']);

        $slug = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '-', $data['name']));

        Database::getInstance()->insert('services', [
            'name' => $data['name'],
            'slug' => $slug,
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'icon' => $request->input('icon'),
            'price_from' => $request->input('price_from') ?: null,
            'is_featured' => (int) ($request->input('is_featured') ?? 0),
            'is_active' => 1,
            'sort_order' => (int) ($request->input('sort_order') ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Logger::audit('Serviço criado', ['name' => $data['name']]);
        $this->session->flash('success', 'Serviço cadastrado!');
        $this->redirect('/admin/servicos');
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('settings.manage');
        $id = (int) $params['id'];

        Database::getInstance()->update('services', [
            'name' => $request->input('name'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'icon' => $request->input('icon'),
            'price_from' => $request->input('price_from') ?: null,
            'is_featured' => (int) ($request->input('is_featured') ?? 0),
            'is_active' => (int) ($request->input('is_active') ?? 1),
            'sort_order' => (int) ($request->input('sort_order') ?? 0),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);

        $this->session->flash('success', 'Serviço atualizado!');
        $this->redirect('/admin/servicos');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('settings.manage');
        Database::getInstance()->delete('services', 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Serviço excluído!');
        $this->redirect('/admin/servicos');
    }
}
