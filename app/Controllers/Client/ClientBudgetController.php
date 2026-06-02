<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class ClientBudgetController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        if (!$clientId) { $this->redirect('/login'); return ''; }

        $budgets = Database::getInstance()->fetchAll("SELECT * FROM budgets WHERE client_id = :cid AND deleted_at IS NULL ORDER BY created_at DESC", ['cid' => $clientId]);
        return $this->view('client/budgets/index', ['title' => 'Meus Orçamentos', 'budgets' => $budgets, 'user' => $this->getUser()], 'client');
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $db = Database::getInstance();

        $budget = $db->fetchOne("SELECT * FROM budgets WHERE id = :id AND client_id = :cid AND deleted_at IS NULL", ['id' => (int) $params['id'], 'cid' => $clientId]);
        if (!$budget) { $this->redirect('/cliente/orcamentos'); return ''; }

        $blocks = $db->fetchAll("SELECT * FROM budget_blocks WHERE budget_id = :id ORDER BY sort_order", ['id' => $budget['id']]);
        return $this->view('client/budgets/show', ['title' => $budget['name'], 'budget' => $budget, 'blocks' => $blocks, 'user' => $this->getUser()], 'client');
    }

    public function approve(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $id = (int) $params['id'];

        $db = Database::getInstance();
        $budget = $db->fetchOne("SELECT id FROM budgets WHERE id = :id AND client_id = :cid", ['id' => $id, 'cid' => $clientId]);
        if (!$budget) { $this->redirect('/cliente/orcamentos'); return; }

        $db->update('budgets', ['status' => 'approved', 'approved_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
        Logger::audit('Orçamento aprovado pelo cliente', ['budget_id' => $id]);
        $this->session->flash('success', 'Orçamento aprovado!');
        $this->redirect("/cliente/orcamentos/{$id}");
    }

    public function reject(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $id = (int) $params['id'];

        $db = Database::getInstance();
        $budget = $db->fetchOne("SELECT id FROM budgets WHERE id = :id AND client_id = :cid", ['id' => $id, 'cid' => $clientId]);
        if (!$budget) { $this->redirect('/cliente/orcamentos'); return; }

        $db->update('budgets', ['status' => 'rejected', 'rejected_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
        Logger::audit('Orçamento recusado pelo cliente', ['budget_id' => $id]);
        $this->session->flash('success', 'Orçamento recusado.');
        $this->redirect("/cliente/orcamentos/{$id}");
    }

    private function getClientId(): ?int
    {
        $user = $this->getUser();
        $client = Database::getInstance()->fetchOne("SELECT id FROM clients WHERE user_id = :uid", ['uid' => $user['id']]);
        return $client ? (int) $client['id'] : null;
    }
}
