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
     * Gera sitemap.xml
     */
    public function sitemap(Request $request, Response $response): void
    {
        $db = Database::getInstance();
        $baseUrl = Config::get('app.url', 'https://lrvweb.com.br');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Páginas estáticas
        $staticPages = ['', '/sobre', '/servicos', '/hospedagem', '/portfolio', '/blog', '/contato'];
        $languages = ['pt', 'en', 'es'];

        foreach ($languages as $lang) {
            foreach ($staticPages as $page) {
                $xml .= "<url><loc>{$baseUrl}/{$lang}{$page}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>";
            }
        }

        // Blog posts
        $posts = $db->fetchAll("SELECT slug, language, updated_at FROM blog_posts WHERE status = 'published' AND deleted_at IS NULL");
        foreach ($posts as $post) {
            $xml .= "<url><loc>{$baseUrl}/{$post['language']}/blog/{$post['slug']}</loc><lastmod>" . date('Y-m-d', strtotime($post['updated_at'])) . "</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>";
        }

        // Portfolio
        $portfolios = $db->fetchAll("SELECT slug, updated_at FROM portfolios WHERE is_active = 1");
        foreach ($portfolios as $item) {
            $xml .= "<url><loc>{$baseUrl}/pt/portfolio/{$item['slug']}</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>";
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
