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

        $apiKey = Config::get('openai.api_key') ?: Config::setting('openai.api_key');
        if (empty($apiKey)) {
            $this->session->flash('error', 'API Key do OpenAI não configurada. Vá em Configurações > Blog IA.');
            $this->redirect('/admin/blog/ia/configuracoes');
            return;
        }

        $language = $request->input('language') ?? 'pt';
        $topic = $request->input('topic') ?? '';
        $model = Config::setting('openai.model') ?: Config::get('openai.model', 'gpt-4');

        $db = Database::getInstance();

        // Registra job
        $jobId = $db->insert('blog_ai_jobs', [
            'status' => 'processing',
            'language' => $language,
            'topic' => $topic,
            'started_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        try {
            $langNames = ['pt' => 'Português Brasileiro', 'en' => 'English', 'es' => 'Español'];
            $langName = $langNames[$language] ?? 'Português Brasileiro';

            $topicInstruction = $topic ? "O tema DEVE ser: {$topic}." : "Escolha um tema relevante sobre desenvolvimento web, hospedagem de sites, marketing digital ou tecnologia para negócios.";

            $prompt = "Gere um artigo de blog profissional. {$topicInstruction}
O artigo deve ser em {$langName}, ter entre 800-1200 palavras e incluir:
- title: Título atrativo (max 70 caracteres)
- excerpt: Resumo curto (max 160 caracteres)
- content: Conteúdo completo em HTML com tags <h2>, <h3>, <p>, <ul>, <li>, <strong>. NÃO use <h1>.
- meta_description: Meta description para SEO (max 155 caracteres)
- keywords: 5 palavras-chave separadas por vírgula
- image_search: Uma palavra-chave em inglês para buscar imagem (ex: 'web hosting', 'coding', 'cloud server')

Retorne APENAS um JSON válido com essas chaves.";

            // Chama OpenAI
            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um redator especialista em marketing digital e tecnologia. Sempre responda em JSON válido.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 3000,
                ]),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    "Authorization: Bearer {$apiKey}",
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 90,
            ]);

            $aiResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || !$aiResponse) {
                throw new \RuntimeException("OpenAI API error (HTTP {$httpCode})");
            }

            $result = json_decode($aiResponse, true);
            $content = $result['choices'][0]['message']['content'] ?? '';

            // Tenta parsear JSON (remove possíveis ```json wrappers)
            $content = preg_replace('/^```json\s*|\s*```$/m', '', trim($content));
            $data = json_decode($content, true);

            if (!$data || !isset($data['title'])) {
                throw new \RuntimeException('Formato de resposta inválido da IA');
            }

            // Busca imagem no Unsplash
            $imageUrl = '';
            $imageSearch = $data['image_search'] ?? $data['keywords'] ?? 'technology';
            $unsplashUrl = 'https://source.unsplash.com/1200x630/?' . urlencode($imageSearch);

            // Baixa a imagem
            $imgDir = ROOT_PATH . '/public/assets/img/blog/';
            if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);
            $imgFilename = 'ai_' . time() . '_' . uniqid() . '.jpg';
            $imgPath = $imgDir . $imgFilename;

            $imgData = @file_get_contents($unsplashUrl);
            if ($imgData && strlen($imgData) > 1000) {
                file_put_contents($imgPath, $imgData);
                $imageUrl = '/assets/img/blog/' . $imgFilename;
            }

            // Cria slug
            $slug = mb_strtolower($data['title']);
            $slug = preg_replace('/[áàãâä]/u', 'a', $slug);
            $slug = preg_replace('/[éèêë]/u', 'e', $slug);
            $slug = preg_replace('/[íìîï]/u', 'i', $slug);
            $slug = preg_replace('/[óòõôö]/u', 'o', $slug);
            $slug = preg_replace('/[úùûü]/u', 'u', $slug);
            $slug = preg_replace('/[ç]/u', 'c', $slug);
            $slug = preg_replace('/[ñ]/u', 'n', $slug);
            $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
            $slug = preg_replace('/[\s-]+/', '-', $slug);
            $slug = trim($slug, '-') . '-' . date('Ymd');

            // Insere o post
            $postId = $db->insert('blog_posts', [
                'title' => $data['title'],
                'slug' => $slug,
                'excerpt' => $data['excerpt'] ?? '',
                'content' => $data['content'] ?? '',
                'image' => $imageUrl,
                'meta_title' => $data['title'],
                'meta_description' => $data['meta_description'] ?? '',
                'meta_keywords' => $data['keywords'] ?? '',
                'tags' => $data['keywords'] ?? '',
                'status' => 'published',
                'is_ai_generated' => 1,
                'language' => $language,
                'author_id' => $this->getUser()['id'],
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Atualiza job como concluído
            $db->update('blog_ai_jobs', [
                'status' => 'completed',
                'post_id' => $postId,
                'generated_title' => $data['title'],
                'generated_content' => $data['content'],
                'generated_meta' => json_encode($data),
                'completed_at' => date('Y-m-d H:i:s'),
            ], 'id = :id', ['id' => $jobId]);

            Logger::audit('Post IA gerado', ['post_id' => $postId, 'title' => $data['title']]);
            $this->session->flash('success', 'Post gerado com sucesso! "' . $data['title'] . '"');
        } catch (\Throwable $e) {
            $db->update('blog_ai_jobs', [
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => date('Y-m-d H:i:s'),
            ], 'id = :id', ['id' => $jobId]);

            Logger::error('Blog IA falhou', ['error' => $e->getMessage()]);
            $this->session->flash('error', 'Erro ao gerar post: ' . $e->getMessage());
        }

        $this->redirect('/admin/blog/ia/configuracoes');
    }

    public function aiSettings(Request $request, Response $response): string
    {
        $this->requirePermission('blog.manage');

        // Busca config do arquivo E do banco (banco tem prioridade)
        $fileConfig = Config::get('openai') ?? [];
        $dbConfig = [
            'api_key' => Config::setting('openai.api_key') ?: ($fileConfig['api_key'] ?? ''),
            'model' => Config::setting('openai.model') ?: ($fileConfig['model'] ?? 'gpt-4'),
            'blog_frequency' => Config::setting('openai.blog_frequency') ?: ($fileConfig['blog_frequency'] ?? 'weekly'),
            'blog_enabled' => Config::setting('openai.blog_enabled') ?: ($fileConfig['blog_enabled'] ?? false),
            'blog_languages' => Config::setting('openai.blog_languages') ?: implode(',', $fileConfig['blog_languages'] ?? ['pt','en','es']),
        ];

        $jobs = Database::getInstance()->fetchAll("SELECT * FROM blog_ai_jobs ORDER BY created_at DESC LIMIT 20");
        return $this->adminView('blog/ai-settings', ['title' => 'Blog IA - Configurações', 'config' => $dbConfig, 'jobs' => $jobs]);
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
