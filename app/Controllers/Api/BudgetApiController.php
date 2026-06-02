<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class BudgetApiController extends Controller
{
    public function index(Request $request, Response $response): void
    {
        $this->requireAuth();
        $budgets = Database::getInstance()->fetchAll("SELECT b.*, c.name as client_name FROM budgets b LEFT JOIN clients c ON b.client_id = c.id WHERE b.deleted_at IS NULL ORDER BY b.created_at DESC");
        $this->response->success($budgets);
    }

    public function show(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $db = Database::getInstance();
        $budget = $db->fetchOne("SELECT * FROM budgets WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$budget) { $this->response->error('Não encontrado', 404); return; }
        $budget['blocks'] = $db->fetchAll("SELECT * FROM budget_blocks WHERE budget_id = :id ORDER BY sort_order", ['id' => $budget['id']]);
        $this->response->success($budget);
    }
}
