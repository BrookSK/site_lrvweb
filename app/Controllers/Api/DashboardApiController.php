<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class DashboardApiController extends Controller
{
    public function stats(Request $request, Response $response): void
    {
        $this->requireAuth();
        $db = Database::getInstance();

        $this->response->success([
            'active_clients' => (int) ($db->fetchOne("SELECT COUNT(*) as t FROM clients WHERE is_active = 1 AND deleted_at IS NULL")['t'] ?? 0),
            'active_projects' => (int) ($db->fetchOne("SELECT COUNT(*) as t FROM projects WHERE status NOT IN ('completed','cancelled') AND deleted_at IS NULL")['t'] ?? 0),
            'pending_budgets' => (int) ($db->fetchOne("SELECT COUNT(*) as t FROM budgets WHERE status IN ('draft','sent') AND deleted_at IS NULL")['t'] ?? 0),
            'monthly_revenue' => (float) ($db->fetchOne("SELECT COALESCE(SUM(value),0) as t FROM financial_entries WHERE type='revenue' AND MONTH(date)=MONTH(NOW()) AND YEAR(date)=YEAR(NOW())")['t'] ?? 0),
        ]);
    }

    public function charts(Request $request, Response $response): void
    {
        $this->requireAuth();
        $db = Database::getInstance();

        $monthlyRevenue = $db->fetchAll("
            SELECT DATE_FORMAT(date, '%Y-%m') as month, SUM(value) as total
            FROM financial_entries WHERE type = 'revenue' AND date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(date, '%Y-%m') ORDER BY month
        ");

        $projectsByStatus = $db->fetchAll("
            SELECT status, COUNT(*) as total FROM projects WHERE deleted_at IS NULL GROUP BY status
        ");

        $this->response->success(['monthly_revenue' => $monthlyRevenue, 'projects_by_status' => $projectsByStatus]);
    }
}
