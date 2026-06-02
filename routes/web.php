<?php

/**
 * Rotas do Site Institucional
 * 
 * Rotas públicas acessíveis sem autenticação.
 */

declare(strict_types=1);

use App\Controllers\Site\HomeController;
use App\Controllers\Site\AboutController;
use App\Controllers\Site\ServicesController;
use App\Controllers\Site\PortfolioController;
use App\Controllers\Site\BlogController;
use App\Controllers\Site\ContactController;
use App\Controllers\Site\HostingController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Site\BudgetPublicController;
use App\Controllers\Site\PageController;

/** @var \Core\Router $router */

// === Multilíngue Redirect ===
$router->get('/', [HomeController::class, 'index']);

// === Rotas com prefixo de idioma ===
$languages = ['pt', 'en', 'es'];

foreach ($languages as $lang) {
    $router->group(['prefix' => "/{$lang}"], function ($router) {
        // Home
        $router->get('/', [HomeController::class, 'index']);

        // Sobre
        $router->get('/sobre', [AboutController::class, 'index']);
        $router->get('/about', [AboutController::class, 'index']);

        // Serviços
        $router->get('/servicos', [ServicesController::class, 'index']);
        $router->get('/services', [ServicesController::class, 'index']);
        $router->get('/servicos/{slug}', [ServicesController::class, 'show']);

        // Hospedagem
        $router->get('/hospedagem', [HostingController::class, 'index']);
        $router->get('/hosting', [HostingController::class, 'index']);

        // Portfólio
        $router->get('/portfolio', [PortfolioController::class, 'index']);
        $router->get('/portfolio/{slug}', [PortfolioController::class, 'show']);

        // Blog
        $router->get('/blog', [BlogController::class, 'index']);
        $router->get('/blog/{slug}', [BlogController::class, 'show']);
        $router->get('/blog/categoria/{slug}', [BlogController::class, 'category']);
        $router->get('/blog/tag/{slug}', [BlogController::class, 'tag']);

        // Contato
        $router->get('/contato', [ContactController::class, 'index']);
        $router->get('/contact', [ContactController::class, 'index']);
        $router->post('/contato', [ContactController::class, 'send']);

        // Páginas dinâmicas (LGPD, Termos, etc.)
        $router->get('/pagina/{slug}', [PageController::class, 'show']);
    });
}

// === Autenticação ===
$router->get('/login', [LoginController::class, 'showLoginForm']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);
$router->get('/recuperar-senha', [LoginController::class, 'showForgotPassword']);
$router->post('/recuperar-senha', [LoginController::class, 'forgotPassword']);
$router->get('/redefinir-senha/{token}', [LoginController::class, 'showResetPassword']);
$router->post('/redefinir-senha', [LoginController::class, 'resetPassword']);

// === Orçamento Público ===
$router->get('/orcamento/{hash}', [BudgetPublicController::class, 'show']);

// === SEO ===
$router->get('/sitemap.xml', [PageController::class, 'sitemap']);
$router->get('/robots.txt', [PageController::class, 'robots']);
