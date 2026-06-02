<?php

/**
 * Rotas da API REST
 * 
 * Endpoints para comunicação com frontend e integrações futuras.
 */

declare(strict_types=1);

use App\Controllers\Api\AuthApiController;
use App\Controllers\Api\ClientApiController;
use App\Controllers\Api\ProjectApiController;
use App\Controllers\Api\BudgetApiController;
use App\Controllers\Api\CrmApiController;
use App\Controllers\Api\DashboardApiController;

/** @var \Core\Router $router */

$router->group(['prefix' => '/api/v1'], function ($router) {

    // Autenticação API
    $router->post('/auth/login', [AuthApiController::class, 'login']);
    $router->post('/auth/refresh', [AuthApiController::class, 'refresh']);

    // Rotas autenticadas
    $router->group(['middlewares' => ['AuthMiddleware']], function ($router) {
        // Dashboard
        $router->get('/dashboard/stats', [DashboardApiController::class, 'stats']);
        $router->get('/dashboard/charts', [DashboardApiController::class, 'charts']);

        // Clientes
        $router->get('/clients', [ClientApiController::class, 'index']);
        $router->get('/clients/{id}', [ClientApiController::class, 'show']);
        $router->post('/clients', [ClientApiController::class, 'store']);
        $router->put('/clients/{id}', [ClientApiController::class, 'update']);
        $router->delete('/clients/{id}', [ClientApiController::class, 'destroy']);

        // Projetos
        $router->get('/projects', [ProjectApiController::class, 'index']);
        $router->get('/projects/{id}', [ProjectApiController::class, 'show']);
        $router->put('/projects/{id}/status', [ProjectApiController::class, 'updateStatus']);

        // Orçamentos
        $router->get('/budgets', [BudgetApiController::class, 'index']);
        $router->get('/budgets/{id}', [BudgetApiController::class, 'show']);

        // CRM
        $router->get('/crm/leads', [CrmApiController::class, 'index']);
        $router->put('/crm/leads/{id}/stage', [CrmApiController::class, 'updateStage']);
    });
});
