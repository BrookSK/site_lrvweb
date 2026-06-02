<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class ServicesController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $services = Database::getInstance()->fetchAll(
            "SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order"
        );

        return $this->view('site/services', [
            'title' => 'Serviços - LRV Web',
            'meta_description' => 'Nossos serviços: hospedagem, criação de sites, sistemas, e-commerce e mais.',
            'services' => $services,
        ], 'site');
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $slug = $params['slug'] ?? '';
        $service = Database::getInstance()->fetchOne(
            "SELECT * FROM services WHERE slug = :slug AND is_active = 1",
            ['slug' => $slug]
        );

        if (!$service) {
            $this->response->setStatusCode(404);
            return \Core\View::render('errors/404');
        }

        return $this->view('site/service-detail', [
            'title' => $service['name'] . ' - LRV Web',
            'service' => $service,
        ], 'site');
    }
}
