<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class PortfolioController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('portfolio.view');
        $portfolios = Database::getInstance()->fetchAll("SELECT p.*, pc.name as category_name FROM portfolios p LEFT JOIN portfolio_categories pc ON p.category_id = pc.id ORDER BY p.sort_order, p.created_at DESC");
        return $this->adminView('portfolio/index', ['title' => 'Portfólio', 'portfolios' => $portfolios]);
    }

    public function create(Request $request, Response $response): string
    {
        $this->requirePermission('portfolio.manage');
        $db = Database::getInstance();
        $categories = $db->fetchAll("SELECT * FROM portfolio_categories ORDER BY sort_order");
        $clients = $db->fetchAll("SELECT id, name FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        return $this->adminView('portfolio/form', ['title' => 'Novo Projeto', 'portfolio' => null, 'categories' => $categories, 'clients' => $clients]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('portfolio.manage');
        $data = $this->validate(['name' => 'required|max:200']);
        $db = Database::getInstance();

        $slug = $this->createSlug($data['name']);

        $portfolioData = [
            'name' => $data['name'],
            'slug' => $slug,
            'category_id' => $request->input('category_id') ?: null,
            'client_id' => $request->input('client_id') ?: null,
            'description' => $request->input('description'),
            'technologies' => $request->input('technologies'),
            'url' => $request->input('url'),
            'is_featured' => (int) ($request->input('is_featured') ?? 0),
            'is_active' => 1,
            'completed_at' => $request->input('completed_at') ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Upload da imagem de capa
        $file = $request->file('image_cover');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $portfolioData['image_cover'] = $this->uploadImage($file, 'portfolio');
        }

        $db->insert('portfolios', $portfolioData);
        Logger::audit('Portfolio criado', ['name' => $data['name']]);
        $this->session->flash('success', 'Projeto adicionado ao portfólio!');
        $this->redirect('/admin/portfolio');
    }

    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('portfolio.manage');
        $db = Database::getInstance();
        $portfolio = $db->fetchOne("SELECT * FROM portfolios WHERE id = :id", ['id' => (int) $params['id']]);
        if (!$portfolio) { $this->redirect('/admin/portfolio'); return ''; }
        $categories = $db->fetchAll("SELECT * FROM portfolio_categories ORDER BY sort_order");
        $clients = $db->fetchAll("SELECT id, name FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        return $this->adminView('portfolio/form', ['title' => 'Editar: ' . $portfolio['name'], 'portfolio' => $portfolio, 'categories' => $categories, 'clients' => $clients]);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('portfolio.manage');
        $id = (int) $params['id'];
        $db = Database::getInstance();

        $updateData = [
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id') ?: null,
            'client_id' => $request->input('client_id') ?: null,
            'description' => $request->input('description'),
            'technologies' => $request->input('technologies'),
            'url' => $request->input('url'),
            'is_featured' => (int) ($request->input('is_featured') ?? 0),
            'is_active' => (int) ($request->input('is_active') ?? 1),
            'completed_at' => $request->input('completed_at') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $file = $request->file('image_cover');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $updateData['image_cover'] = $this->uploadImage($file, 'portfolio');
        }

        $db->update('portfolios', $updateData, 'id = :id', ['id' => $id]);
        $this->session->flash('success', 'Portfólio atualizado!');
        $this->redirect('/admin/portfolio');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('portfolio.manage');
        Database::getInstance()->delete('portfolios', 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Item removido do portfólio!');
        $this->redirect('/admin/portfolio');
    }

    private function uploadImage(array $file, string $folder): string
    {
        $uploadDir = ROOT_PATH . "/public/assets/img/{$folder}/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return "/assets/img/{$folder}/{$filename}";
    }

    private function createSlug(string $text): string
    {
        $slug = mb_strtolower($text);
        $slug = preg_replace('/[áàãâä]/u', 'a', $slug);
        $slug = preg_replace('/[éèêë]/u', 'e', $slug);
        $slug = preg_replace('/[íìîï]/u', 'i', $slug);
        $slug = preg_replace('/[óòõôö]/u', 'o', $slug);
        $slug = preg_replace('/[úùûü]/u', 'u', $slug);
        $slug = preg_replace('/[ç]/u', 'c', $slug);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        return trim($slug, '-');
    }
}
