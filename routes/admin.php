<?php

/**
 * Rotas do Painel Administrativo
 * 
 * Todas as rotas exigem autenticação e permissão de administrador.
 */

declare(strict_types=1);

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ClientController;
use App\Controllers\Admin\CrmController;
use App\Controllers\Admin\ProjectController;
use App\Controllers\Admin\BudgetController;
use App\Controllers\Admin\FinancialController;
use App\Controllers\Admin\DocumentController;
use App\Controllers\Admin\TeamController;
use App\Controllers\Admin\PortfolioController;
use App\Controllers\Admin\BlogController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\VersionController;
use App\Controllers\Admin\BackupController;
use App\Controllers\Admin\LogController;
use App\Controllers\Admin\PageController;
use App\Controllers\Admin\ServiceController;
use App\Controllers\Admin\NotificationController;

/** @var \Core\Router $router */

$router->group(['prefix' => '/admin', 'middlewares' => ['AuthMiddleware', 'AdminMiddleware']], function ($router) {

    // Dashboard
    $router->get('/', [DashboardController::class, 'index']);
    $router->get('/dashboard', [DashboardController::class, 'index']);

    // Clientes
    $router->get('/clientes', [ClientController::class, 'index']);
    $router->get('/clientes/criar', [ClientController::class, 'create']);
    $router->post('/clientes', [ClientController::class, 'store']);
    $router->get('/clientes/{id}', [ClientController::class, 'show']);
    $router->get('/clientes/{id}/editar', [ClientController::class, 'edit']);
    $router->put('/clientes/{id}', [ClientController::class, 'update']);
    $router->delete('/clientes/{id}', [ClientController::class, 'destroy']);

    // CRM / Leads
    $router->get('/crm', [CrmController::class, 'index']);
    $router->get('/crm/kanban', [CrmController::class, 'kanban']);
    $router->post('/crm/leads', [CrmController::class, 'store']);
    $router->put('/crm/leads/{id}', [CrmController::class, 'update']);
    $router->put('/crm/leads/{id}/status', [CrmController::class, 'updateStatus']);
    $router->delete('/crm/leads/{id}', [CrmController::class, 'destroy']);

    // Projetos
    $router->get('/projetos', [ProjectController::class, 'index']);
    $router->get('/projetos/kanban', [ProjectController::class, 'kanban']);
    $router->get('/projetos/criar', [ProjectController::class, 'create']);
    $router->post('/projetos', [ProjectController::class, 'store']);
    $router->get('/projetos/{id}', [ProjectController::class, 'show']);
    $router->get('/projetos/{id}/editar', [ProjectController::class, 'edit']);
    $router->put('/projetos/{id}', [ProjectController::class, 'update']);
    $router->delete('/projetos/{id}', [ProjectController::class, 'destroy']);
    $router->post('/projetos/{id}/tarefas', [ProjectController::class, 'addTask']);
    $router->put('/projetos/tarefas/{id}', [ProjectController::class, 'updateTask']);

    // Orçamentos
    $router->get('/orcamentos', [BudgetController::class, 'index']);
    $router->get('/orcamentos/criar', [BudgetController::class, 'create']);
    $router->post('/orcamentos', [BudgetController::class, 'store']);
    $router->get('/orcamentos/{id}', [BudgetController::class, 'show']);
    $router->get('/orcamentos/{id}/editar', [BudgetController::class, 'edit']);
    $router->put('/orcamentos/{id}', [BudgetController::class, 'update']);
    $router->delete('/orcamentos/{id}', [BudgetController::class, 'destroy']);
    $router->post('/orcamentos/{id}/blocos', [BudgetController::class, 'addBlock']);
    $router->put('/orcamentos/blocos/{id}', [BudgetController::class, 'updateBlock']);
    $router->delete('/orcamentos/blocos/{id}', [BudgetController::class, 'deleteBlock']);
    $router->get('/orcamentos/{id}/duplicar', [BudgetController::class, 'duplicate']);
    $router->get('/orcamentos/{id}/pdf', [BudgetController::class, 'generatePdf']);

    // Financeiro
    $router->get('/financeiro', [FinancialController::class, 'index']);
    $router->get('/financeiro/receitas', [FinancialController::class, 'revenues']);
    $router->get('/financeiro/despesas', [FinancialController::class, 'expenses']);
    $router->get('/financeiro/fluxo-caixa', [FinancialController::class, 'cashFlow']);
    $router->post('/financeiro/lancamentos', [FinancialController::class, 'store']);
    $router->put('/financeiro/lancamentos/{id}', [FinancialController::class, 'update']);
    $router->delete('/financeiro/lancamentos/{id}', [FinancialController::class, 'destroy']);

    // Documentos
    $router->get('/documentos', [DocumentController::class, 'index']);
    $router->post('/documentos/upload', [DocumentController::class, 'upload']);
    $router->get('/documentos/{id}/download', [DocumentController::class, 'download']);
    $router->put('/documentos/{id}', [DocumentController::class, 'update']);
    $router->delete('/documentos/{id}', [DocumentController::class, 'destroy']);

    // Equipe
    $router->get('/equipe', [TeamController::class, 'index']);
    $router->get('/equipe/criar', [TeamController::class, 'create']);
    $router->post('/equipe', [TeamController::class, 'store']);
    $router->get('/equipe/{id}/editar', [TeamController::class, 'edit']);
    $router->put('/equipe/{id}', [TeamController::class, 'update']);
    $router->delete('/equipe/{id}', [TeamController::class, 'destroy']);
    $router->put('/equipe/{id}/permissoes', [TeamController::class, 'updatePermissions']);

    // Portfólio
    $router->get('/portfolio', [PortfolioController::class, 'index']);
    $router->get('/portfolio/criar', [PortfolioController::class, 'create']);
    $router->post('/portfolio', [PortfolioController::class, 'store']);
    $router->get('/portfolio/{id}/editar', [PortfolioController::class, 'edit']);
    $router->put('/portfolio/{id}', [PortfolioController::class, 'update']);
    $router->delete('/portfolio/{id}', [PortfolioController::class, 'destroy']);

    // Blog
    $router->get('/blog', [BlogController::class, 'index']);
    $router->get('/blog/criar', [BlogController::class, 'create']);
    $router->post('/blog', [BlogController::class, 'store']);
    $router->get('/blog/{id}/editar', [BlogController::class, 'edit']);
    $router->put('/blog/{id}', [BlogController::class, 'update']);
    $router->delete('/blog/{id}', [BlogController::class, 'destroy']);
    $router->post('/blog/ia/gerar', [BlogController::class, 'generateWithAi']);
    $router->get('/blog/ia/configuracoes', [BlogController::class, 'aiSettings']);
    $router->put('/blog/ia/configuracoes', [BlogController::class, 'updateAiSettings']);

    // Serviços
    $router->get('/servicos', [ServiceController::class, 'index']);
    $router->post('/servicos', [ServiceController::class, 'store']);
    $router->put('/servicos/{id}', [ServiceController::class, 'update']);
    $router->delete('/servicos/{id}', [ServiceController::class, 'destroy']);

    // Páginas (CMS)
    $router->get('/paginas', [PageController::class, 'index']);
    $router->get('/paginas/{id}/editar', [PageController::class, 'edit']);
    $router->put('/paginas/{id}', [PageController::class, 'update']);

    // Configurações
    $router->get('/configuracoes', [SettingsController::class, 'index']);
    $router->put('/configuracoes', [SettingsController::class, 'update']);
    $router->post('/configuracoes/logo', [SettingsController::class, 'uploadLogo']);

    // Versionamento
    $router->get('/versoes', [VersionController::class, 'index']);
    $router->post('/versoes', [VersionController::class, 'store']);
    $router->get('/versoes/changelog', [VersionController::class, 'changelog']);
    $router->get('/versoes/changelog/exportar', [VersionController::class, 'exportChangelog']);

    // Backups
    $router->get('/backups', [BackupController::class, 'index']);
    $router->post('/backups/criar', [BackupController::class, 'create']);
    $router->get('/backups/{id}/download', [BackupController::class, 'download']);
    $router->post('/backups/{id}/restaurar', [BackupController::class, 'restore']);
    $router->delete('/backups/{id}', [BackupController::class, 'destroy']);

    // Logs / Auditoria
    $router->get('/logs', [LogController::class, 'index']);
    $router->get('/logs/auditoria', [LogController::class, 'audit']);

    // Notificações
    $router->get('/notificacoes', [NotificationController::class, 'index']);
    $router->put('/notificacoes/{id}/lida', [NotificationController::class, 'markAsRead']);
    $router->put('/notificacoes/todas-lidas', [NotificationController::class, 'markAllAsRead']);
});
