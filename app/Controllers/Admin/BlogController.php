<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Config;
use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class BlogController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('blog.view');
        $posts = Database::getInstance()->fetchAll("
            SELECT p.*, bc.name as category_name
            FROM blog_posts p
            LEFT JOIN blog_categories bc ON p.category_id = bc.id
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC
        ");
        return $this->adminView('blog/index', ['title' => 'Blog', 'posts' => $posts]);
    }

    public function create(Request $request, Response $response): string
    {
        $this->requirePermission('blog.manage');
        $categories = Database::getInstance()->fetchAll("SELECT * FROM blog_categories ORDER BY name");
        return $this->adminView('blog/form', ['title' => 'Novo Post', 'post' => null, 'categories' => $categories]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('blog.manage');
        $data = $this->validate(['title' => 'required|max:255']);
        $db = Database::getInstance();

        $slug = $this->createSlug($data['title']);
        $existing = $db->fetchOne("SELECT id FROM blog_posts WHERE slug = :slug", ['slug' => $slug]);
        if ($existing) $slug .= '-' . time();

        $postData = [
            'title' => $data['title'],
            'slug' => $slug,
            'category_id' => $request->input('category_id') ?: null,
            'author_id' => $this->getUser()['id'],
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'tags' => $request->input('tags'),
            'meta_title' => $request->input('meta_title') ?: $data['title'],
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'status' => $request->input('status') ?? 'draft',
            'language' => $request->input('language') ?? 'pt',
            'published_at' => $request->input('status') === 'published' ? date('Y-m-d H:i:s') : null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $postData['image'] = $this->uploadImage($file);
        }

        $id = $db->insert('blog_posts', $postData);
        Logger::audit('Post criado', ['post_id' => $id]);
        $this->session->flash('success', 'Post criado!');
        $this->redirect("/admin/blog/{$id}/editar");
    }

    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('blog.manage');
        $db = Database::getInstance();
        $post = $db->fetchOne("SELECT * FROM blog_posts WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$post) { $this->redirect('/admin/blog'); return ''; }
        $categories = $db->fetchAll("SELECT * FROM blog_categories ORDER BY name");
        return $this->adminView('blog/form', ['title' => 'Editar: ' . $post['title'], 'post' => $post, 'categories' => $categories]);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('blog.manage');
        $id = (int) $params['id'];
        $db = Database::getInstance();

        $updateData = [
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id') ?: null,
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'tags' => $request->input('tags'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'status' => $request->input('status') ?? 'draft',
            'language' => $request->input('language') ?? 'pt',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($request->input('status') === 'published') {
            $current = $db->fetchOne("SELECT published_at FROM blog_posts WHERE id = :id", ['id' => $id]);
            if (!$current['published_at']) $updateData['published_at'] = date('Y-m-d H:i:s');
        }

        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $updateData['image'] = $this->uploadImage($file);
        }

        $db->update('blog_posts', $updateData, 'id = :id', ['id' => $id]);
        $this->session->flash('success', 'Post atualizado!');
        $this->redirect("/admin/blog/{$id}/editar");
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('blog.manage');
        Database::getInstance()->update('blog_posts', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Post excluído!');
        $this->redirect('/admin/blog');
    }

    public function generateWithAi(Request $request, Response $response): void
    {
        $this->requirePermission('blog.manage');

        $apiKey = Config::get('openai.api_key');
        if (empty($apiKey)) {
            $this->session->flash('error', 'API Key do OpenAI não configurada. Vá em Configurações > Blog IA.');
            $this->redirect('/admin/blog');
            return;
        }

        $language = $request->input('language') ?? 'pt';
        $topic = $request->input('topic') ?? '';

        // Dispara geração (sincrona para simplificar)
        $db = Database::getInstance();
        $jobId = $db->insert('blog_ai_jobs', [
            'status' => 'processing',
            'language' => $language,
            'topic' => $topic,
            'started_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // TODO: Implementar chamada real à API aqui ou via worker
        $this->session->flash('success', 'Geração de post com IA iniciada! Aguarde a conclusão.');
        $this->redirect('/admin/blog');
    }

    public function aiSettings(Request $request, Response $response): string
    {
        $this->requirePermission('blog.manage');
        $config = Config::get('openai');
        $jobs = Database::getInstance()->fetchAll("SELECT * FROM blog_ai_jobs ORDER BY created_at DESC LIMIT 20");
        return $this->adminView('blog/ai-settings', ['title' => 'Blog IA - Configurações', 'config' => $config, 'jobs' => $jobs]);
    }

    public function updateAiSettings(Request $request, Response $response): void
    {
        $this->requirePermission('blog.manage');
        // Redireciona para a aba de configurações de IA
        $this->redirect('/admin/configuracoes?tab=openai');
    }

    private function uploadImage(array $file): string
    {
        $uploadDir = ROOT_PATH . '/public/assets/img/blog/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return '/assets/img/blog/' . $filename;
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
