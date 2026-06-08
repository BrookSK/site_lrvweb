<?php

/**
 * Controller da Home do Site
 * 
 * @package App\Controllers\Site
 */

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class HomeController extends Controller
{
    /**
     * Redireciona URLs antigas (sem prefixo de idioma) para as novas
     * Redirect 301 para preservar SEO
     */
    public function redirectOld(Request $request, Response $response, array $params = []): void
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $newUrl = '/pt' . $uri;
        $response->setStatusCode(301);
        $response->setHeader('Location', $newUrl);
        $response->setHeader('Cache-Control', 'public, max-age=86400');
        $response->send();
    }

    /**
     * Página inicial
     */
    public function index(Request $request, Response $response): string
    {
        $db = Database::getInstance();

        // Serviços em destaque
        $services = $db->fetchAll("
            SELECT name, slug, short_description, icon, image
            FROM services 
            WHERE is_active = 1 AND is_featured = 1
            ORDER BY sort_order
            LIMIT 6
        ");

        // Portfólio recente
        $portfolio = $db->fetchAll("
            SELECT p.name, p.slug, p.image_cover, p.description, pc.name as category
            FROM portfolios p
            LEFT JOIN portfolio_categories pc ON p.category_id = pc.id
            WHERE p.is_active = 1
            ORDER BY p.sort_order, p.created_at DESC
            LIMIT 6
        ");

        // Posts do blog
        $posts = $db->fetchAll("
            SELECT title, slug, excerpt, image, published_at
            FROM blog_posts
            WHERE status = 'published' AND deleted_at IS NULL
            ORDER BY published_at DESC
            LIMIT 3
        ");

        // Settings
        $settings = $this->getSiteSettings($db);

        return $this->view('site/home', [
            'title' => $settings['meta_title'] ?? 'LRV Web',
            'meta_description' => $settings['meta_description'] ?? '',
            'services' => $services,
            'portfolio' => $portfolio,
            'posts' => $posts,
            'settings' => $settings,
        ], 'site');
    }

    /**
     * Carrega configurações do site
     */
    private function getSiteSettings(Database $db): array
    {
        $rows = $db->fetchAll("SELECT `group`, `key`, value FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
}
