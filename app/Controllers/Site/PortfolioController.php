<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class PortfolioController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $db = Database::getInstance();
        $category = $request->query('categoria');

        $where = 'p.is_active = 1';
        $params = [];

        if ($category) {
            $where .= ' AND pc.slug = :category';
            $params['category'] = $category;
        }

        $portfolios = $db->fetchAll("
            SELECT p.*, pc.name as category_name, pc.slug as category_slug
            FROM portfolios p
            LEFT JOIN portfolio_categories pc ON p.category_id = pc.id
            WHERE {$where}
            ORDER BY p.sort_order, p.created_at DESC
        ", $params);

        $categories = $db->fetchAll("SELECT * FROM portfolio_categories ORDER BY sort_order");

        return $this->view('site/portfolio', [
            'title' => 'Portfólio - LRV Web',
            'meta_description' => 'Conheça nossos projetos: sites, sistemas, e-commerces e mais.',
            'portfolios' => $portfolios,
            'categories' => $categories,
            'currentCategory' => $category,
        ], 'site');
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $slug = $params['slug'] ?? '';
        $db = Database::getInstance();

        $portfolio = $db->fetchOne("
            SELECT p.*, pc.name as category_name, c.name as client_name
            FROM portfolios p
            LEFT JOIN portfolio_categories pc ON p.category_id = pc.id
            LEFT JOIN clients c ON p.client_id = c.id
            WHERE p.slug = :slug AND p.is_active = 1
        ", ['slug' => $slug]);

        if (!$portfolio) {
            $this->response->setStatusCode(404);
            return \Core\View::render('errors/404');
        }

        return $this->view('site/portfolio-detail', [
            'title' => $portfolio['name'] . ' - Portfólio LRV Web',
            'portfolio' => $portfolio,
        ], 'site');
    }
}
