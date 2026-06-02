<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Request;
use Core\Response;

class AboutController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        return $this->view('site/about', [
            'title' => 'Sobre Nós - LRV Web',
            'meta_description' => 'Conheça a LRV Web, nossa história, equipe e valores.',
        ], 'site');
    }
}
