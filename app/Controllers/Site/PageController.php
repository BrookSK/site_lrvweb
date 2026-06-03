<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Config;
use Core\Database;
use Core\Request;
use Core\Response;

class PageController extends Controller
{
    /**
     * Exibe página dinâmica pelo slug
     */
    public function show(Request $request, Response $response, array $params): string
    {
        $slug = $params['slug'] ?? '';
        $page = Database::getInstance()->fetchOne(
            "SELECT * FROM pages WHERE slug = :slug AND is_active = 1",
            ['slug' => $slug]
        );

        if (!$page) {
            $this->response->setStatusCode(404);
            return \Core\View::render('errors/404');
        }

        return $this->view('site/page', [
            'title' => $page['meta_title'] ?: $page['title'],
            'meta_description' => $page['meta_description'] ?? '',
            'page' => $page,
        ], 'site');
    }

    /**
     * Cron via URL — Gera post com IA
     * URL: /cron/blog-ai/{token}
     * O token deve ser o APP_KEY do config/app.php
     */
    public function cronBlogAi(Request $request, Response $response, array $params): void
    {
        $token = $params['token'] ?? '';
        $appKey = Config::get('app.key', '');

        // Valida token de segurança
        if (empty($appKey) || $token !== $appKey) {
            $response->setStatusCode(403);
            $response->json(['error' => 'Token inválido']);
            return;
        }

        $apiKey = Config::get('openai.api_key') ?: Config::setting('openai.api_key');
        if (empty($apiKey)) {
            $response->json(['error' => 'API Key OpenAI não configurada']);
            return;
        }

        $enabled = Config::setting('openai.blog_enabled') ?: Config::get('openai.blog_enabled', false);
        if (!$enabled) {
            $response->json(['error' => 'Blog IA desativado']);
            return;
        }

        // Verifica frequência — quantos posts já foram gerados esta semana
        $db = Database::getInstance();
        $postsPerWeek = (int) (Config::setting('openai.blog_posts_per_week') ?: 3);
        $postsThisWeek = $db->fetchOne("
            SELECT COUNT(*) as total FROM blog_posts 
            WHERE is_ai_generated = 1 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");

        if ((int)($postsThisWeek['total'] ?? 0) >= $postsPerWeek) {
            $response->json([
                'success' => true, 
                'skipped' => true, 
                'message' => "Limite semanal atingido ({$postsPerWeek} posts/semana). Já foram gerados {$postsThisWeek['total']} nos últimos 7 dias."
            ]);
            return;
        }

        // Executa geração
        $languages = Config::setting('openai.blog_languages') ?: implode(',', Config::get('openai.blog_languages', ['pt']));
        $langArray = array_filter(explode(',', $languages));
        $model = Config::setting('openai.model') ?: Config::get('openai.model', 'gpt-4');
        $db = Database::getInstance();
        $results = [];

        foreach ($langArray as $language) {
            $language = trim($language);

            $jobId = $db->insert('blog_ai_jobs', [
                'status' => 'processing',
                'language' => $language,
                'started_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            try {
                $langNames = ['pt' => 'Português Brasileiro', 'en' => 'English', 'es' => 'Español'];
                $langName = $langNames[$language] ?? 'Português Brasileiro';

                $prompt = "Gere um artigo de blog profissional sobre desenvolvimento web, hospedagem de sites, marketing digital ou tecnologia para negócios.
O artigo deve ser em {$langName}, ter entre 800-1200 palavras e incluir:
- title: Título atrativo (max 70 caracteres)
- excerpt: Resumo curto (max 160 caracteres)
- content: Conteúdo completo em HTML com tags h2, h3, p, ul, li, strong. NÃO use h1.
- meta_description: Meta description para SEO (max 155 caracteres)
- keywords: 5 palavras-chave separadas por vírgula
- image_search: Uma palavra-chave em inglês para buscar imagem

Retorne APENAS um JSON válido com essas chaves.";

                $ch = curl_init('https://api.openai.com/v1/chat/completions');
                curl_setopt_array($ch, [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode([
                        'model' => $model,
                        'messages' => [
                            ['role' => 'system', 'content' => 'Você é um redator especialista. Sempre responda em JSON válido.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.7,
                        'max_tokens' => 3000,
                    ]),
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json', "Authorization: Bearer {$apiKey}"],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 90,
                ]);
                $aiResponse = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode !== 200) throw new \RuntimeException("OpenAI HTTP {$httpCode}");

                $aiResult = json_decode($aiResponse, true);
                $raw = $aiResult['choices'][0]['message']['content'] ?? '';
                $raw = preg_replace('/^```json\s*|\s*```$/m', '', trim($raw));
                $data = json_decode($raw, true);

                if (!$data || !isset($data['title'])) throw new \RuntimeException('JSON inválido da IA');

                // Imagem
                $imageUrl = '';
                $imgSearch = $data['image_search'] ?? 'technology';
                $imgDir = ROOT_PATH . '/public/assets/img/blog/';
                if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);
                $imgFile = 'ai_' . time() . '_' . uniqid() . '.jpg';
                $imgData = @file_get_contents('https://source.unsplash.com/1200x630/?' . urlencode($imgSearch));
                if ($imgData && strlen($imgData) > 1000) {
                    file_put_contents($imgDir . $imgFile, $imgData);
                    $imageUrl = '/assets/img/blog/' . $imgFile;
                }

                // Slug
                $slug = mb_strtolower($data['title']);
                $slug = preg_replace('/[áàãâä]/u', 'a', $slug);
                $slug = preg_replace('/[éèêë]/u', 'e', $slug);
                $slug = preg_replace('/[íìîï]/u', 'i', $slug);
                $slug = preg_replace('/[óòõôö]/u', 'o', $slug);
                $slug = preg_replace('/[úùûü]/u', 'u', $slug);
                $slug = preg_replace('/[ç]/u', 'c', $slug);
                $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
                $slug = preg_replace('/[\s-]+/', '-', $slug);
                $slug = trim($slug, '-') . '-' . date('Ymd');

                $postId = $db->insert('blog_posts', [
                    'title' => $data['title'], 'slug' => $slug, 'excerpt' => $data['excerpt'] ?? '',
                    'content' => $data['content'] ?? '', 'image' => $imageUrl,
                    'meta_title' => $data['title'], 'meta_description' => $data['meta_description'] ?? '',
                    'meta_keywords' => $data['keywords'] ?? '', 'tags' => $data['keywords'] ?? '',
                    'status' => 'published', 'is_ai_generated' => 1, 'language' => $language,
                    'published_at' => date('Y-m-d H:i:s'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $db->update('blog_ai_jobs', ['status' => 'completed', 'post_id' => $postId, 'generated_title' => $data['title'], 'completed_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $jobId]);
                $results[] = ['lang' => $language, 'status' => 'ok', 'title' => $data['title']];
            } catch (\Throwable $e) {
                $db->update('blog_ai_jobs', ['status' => 'failed', 'error_message' => $e->getMessage(), 'completed_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $jobId]);
                $results[] = ['lang' => $language, 'status' => 'error', 'message' => $e->getMessage()];
            }
        }

        $response->json(['success' => true, 'results' => $results]);
    }

    /**
     * Gera sitemap.xml
     */
    public function sitemap(Request $request, Response $response): void
    {
        $db = Database::getInstance();
        $baseUrl = Config::get('app.url', 'https://lrvweb.com.br');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $staticPages = ['', '/sobre', '/servicos', '/hospedagem', '/portfolio', '/blog', '/contato'];
        $languages = ['pt', 'en', 'es'];

        foreach ($languages as $lang) {
            foreach ($staticPages as $page) {
                $xml .= "<url><loc>{$baseUrl}/{$lang}{$page}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>";
            }
        }

        $posts = $db->fetchAll("SELECT slug, language, updated_at FROM blog_posts WHERE status = 'published' AND deleted_at IS NULL");
        foreach ($posts as $post) {
            $xml .= "<url><loc>{$baseUrl}/{$post['language']}/blog/{$post['slug']}</loc><lastmod>" . date('Y-m-d', strtotime($post['updated_at'])) . "</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>";
        }

        $xml .= '</urlset>';

        $response->setHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->setBody($xml);
        $response->send();
    }

    /**
     * Gera robots.txt
     */
    public function robots(Request $request, Response $response): void
    {
        $baseUrl = Config::get('app.url', 'https://lrvweb.com.br');

        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /cliente/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /login\n";
        $robots .= "\n";
        $robots .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        $response->setHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->setBody($robots);
        $response->send();
    }
}
