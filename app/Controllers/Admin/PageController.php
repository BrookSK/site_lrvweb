<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class PageController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('settings.manage');
        $pages = Database::getInstance()->fetchAll("SELECT * FROM pages ORDER BY title");
        return $this->adminView('pages/index', ['title' => 'Páginas (CMS)', 'pages' => $pages]);
    }

    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('settings.manage');
        $page = Database::getInstance()->fetchOne("SELECT * FROM pages WHERE id = :id", ['id' => (int) $params['id']]);
        if (!$page) { $this->redirect('/admin/paginas'); return ''; }
        return $this->adminView('pages/form', ['title' => 'Editar: ' . $page['title'], 'page' => $page]);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('settings.manage');
        $id = (int) $params['id'];

        Database::getInstance()->update('pages', [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'is_active' => (int) ($request->input('is_active') ?? 1),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);

        Logger::audit('Página atualizada', ['page_id' => $id]);
        $this->session->flash('success', 'Página atualizada!');
        $this->redirect("/admin/paginas/{$id}/editar");
    }
}
