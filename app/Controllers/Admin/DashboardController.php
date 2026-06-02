<?php

/**
 * Controller do Dashboard Administrativo
 * 
 * Exibe indicadores, gráficos e atividades recentes.
 * 
 * @package App\Controllers\Admin
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard
     */
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $db = Database::getInstance();

        // Cards
        $stats = [
            'active_clients' => $this->getCount($db, 'clients', 'is_active = 1 AND deleted_at IS NULL'),
            'active_projects' => $this->getCount($db, 'projects', "status NOT IN ('completed', 'cancelled') AND deleted_at IS NULL"),
            'pending_budgets' => $this->getCount($db, 'budgets', "status IN ('draft', 'sent') AND deleted_at IS NULL"),
            'monthly_revenue' => $this->getMonthlyRevenue($db),
            'open_tickets' => $this->getCount($db, 'tickets', "status IN ('open', 'in_progress')"),
            'pending_tasks' => $this->getCount($db, 'project_tasks', "status IN ('pending', 'in_progress')"),
        ];

        // Atividades recentes
        $recentActivities = $db->fetchAll("
            SELECT action, module, entity_type, created_at, 
                   (SELECT name FROM users WHERE id = audit_logs.user_id) as user_name
            FROM audit_logs 
            ORDER BY created_at DESC 
            LIMIT 10
        ");

        // Projetos recentes
        $recentProjects = $db->fetchAll("
            SELECT p.*, c.name as client_name
            FROM projects p
            LEFT JOIN clients c ON p.client_id = c.id
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC
            LIMIT 5
        ");

        return $this->adminView('dashboard/index', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'recentProjects' => $recentProjects,
        ]);
    }

    /**
     * Conta registros com condição
     */
    private function getCount(Database $db, string $table, string $where = '1=1'): int
    {
        $result = $db->fetchOne("SELECT COUNT(*) as total FROM {$table} WHERE {$where}");
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Calcula receita mensal
     */
    private function getMonthlyRevenue(Database $db): float
    {
        $result = $db->fetchOne("
            SELECT COALESCE(SUM(value), 0) as total 
            FROM financial_entries 
            WHERE type = 'revenue' 
            AND MONTH(date) = MONTH(CURRENT_DATE()) 
            AND YEAR(date) = YEAR(CURRENT_DATE())
        ");
        return (float) ($result['total'] ?? 0);
    }
}
