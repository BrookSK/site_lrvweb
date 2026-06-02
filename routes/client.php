<?php

/**
 * Rotas da Área do Cliente
 * 
 * Área exclusiva para clientes visualizarem seus dados.
 */

declare(strict_types=1);

use App\Controllers\Client\ClientDashboardController;
use App\Controllers\Client\ClientProjectController;
use App\Controllers\Client\ClientBudgetController;
use App\Controllers\Client\ClientDocumentController;
use App\Controllers\Client\ClientFinancialController;
use App\Controllers\Client\ClientTicketController;

/** @var \Core\Router $router */

$router->group(['prefix' => '/cliente', 'middlewares' => ['AuthMiddleware']], function ($router) {

    // Dashboard do cliente
    $router->get('/', [ClientDashboardController::class, 'index']);
    $router->get('/dashboard', [ClientDashboardController::class, 'index']);

    // Projetos do cliente
    $router->get('/projetos', [ClientProjectController::class, 'index']);
    $router->get('/projetos/{id}', [ClientProjectController::class, 'show']);

    // Orçamentos
    $router->get('/orcamentos', [ClientBudgetController::class, 'index']);
    $router->get('/orcamentos/{id}', [ClientBudgetController::class, 'show']);
    $router->post('/orcamentos/{id}/aprovar', [ClientBudgetController::class, 'approve']);
    $router->post('/orcamentos/{id}/recusar', [ClientBudgetController::class, 'reject']);

    // Documentos
    $router->get('/documentos', [ClientDocumentController::class, 'index']);
    $router->get('/documentos/{id}/download', [ClientDocumentController::class, 'download']);

    // Financeiro
    $router->get('/financeiro', [ClientFinancialController::class, 'index']);
    $router->get('/financeiro/faturas', [ClientFinancialController::class, 'invoices']);

    // Chamados/Suporte
    $router->get('/chamados', [ClientTicketController::class, 'index']);
    $router->get('/chamados/criar', [ClientTicketController::class, 'create']);
    $router->post('/chamados', [ClientTicketController::class, 'store']);
    $router->get('/chamados/{id}', [ClientTicketController::class, 'show']);
    $router->post('/chamados/{id}/responder', [ClientTicketController::class, 'reply']);
});
