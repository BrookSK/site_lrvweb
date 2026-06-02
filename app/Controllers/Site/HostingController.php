<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Request;
use Core\Response;

class HostingController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        return $this->view('site/hosting', [
            'title' => 'Hospedagem de Sites - LRV Web',
            'meta_description' => 'Planos de hospedagem rápidos e seguros. Hospedagem, VPS, Backup e E-mail profissional.',
        ], 'site');
    }
}
