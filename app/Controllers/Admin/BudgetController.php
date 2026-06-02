<?php

/**
 * Controller de Orçamentos (Admin)
 * 
 * Gerencia criação, edição, blocos e geração de link público.
 * 
 * @package App\Controllers\Admin
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class BudgetController extends Controller
{
    /**
     * Lista orçamentos
     */
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('budgets.view');
        $db = Database::getInstance();

        $page = (int) ($request->query('page') ?? 1);
        $status = $request->query('status');

        $where = 'b.deleted_at IS NULL';
        $params = [];

        if ($status) {
            $where .= ' AND b.status = :status';
            $params['status'] = $status;
        }

        $budgets = $db->fetchAll("
            SELECT b.*, c.name as client_name, c.company as client_company,
                   u.name as created_by_name
            FROM budgets b
            LEFT JOIN clients c ON b.client_id = c.id
            LEFT JOIN users u ON b.created_by = u.id
            WHERE {$where}
            ORDER BY b.created_at DESC
            LIMIT 20 OFFSET " . (($page - 1) * 20), $params);

        return $this->adminView('budgets/index', [
            'title' => 'Orçamentos',
            'budgets' => $budgets,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Formulário de criação
     */
    public function create(Request $request, Response $response): string
    {
        $this->requirePermission('budgets.create');
        $db = Database::getInstance();

        $clients = $db->fetchAll("SELECT id, name, company FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        $portfolios = $db->fetchAll("SELECT id, name, image_cover, slug FROM portfolios WHERE is_active = 1 ORDER BY sort_order");
        $settings = $this->getBudgetSettings($db);

        return $this->adminView('budgets/form', [
            'title' => 'Novo Orçamento',
            'budget' => null,
            'clients' => $clients,
            'portfolios' => $portfolios,
            'settings' => $settings,
        ]);
    }

    /**
     * Salva novo orçamento
     */
    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('budgets.create');

        $data = $this->validate([
            'name' => 'required|max:255',
            'client_id' => 'required|integer',
            'validity_date' => 'date',
            'payment_type' => 'required|in:one_time,monthly,installments',
        ]);

        $db = Database::getInstance();
        $user = $this->getUser();

        $hash = bin2hex(random_bytes(16));

        $budgetData = [
            'name' => $data['name'],
            'client_id' => $data['client_id'],
            'hash' => $hash,
            'status' => 'draft',
            'payment_type' => $data['payment_type'],
            'validity_date' => $data['validity_date'] ?? null,
            'payment_pix' => $request->input('payment_pix') ? 1 : 0,
            'payment_card' => $request->input('payment_card') ? 1 : 0,
            'payment_boleto' => $request->input('payment_boleto') ? 1 : 0,
            'discount_percent' => (float) ($request->input('discount_percent') ?? 0),
            'minimum_entry' => $request->input('minimum_entry') ?: null,
            'installments' => (int) ($request->input('installments') ?? 1),
            'notes' => $request->input('notes'),
            'internal_notes' => $request->input('internal_notes'),
            'about_company' => $request->input('about_company'),
            'created_by' => $user['id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $budgetId = $db->insert('budgets', $budgetData);

        Logger::audit('Orçamento criado', [
            'budget_id' => $budgetId,
            'client_id' => $data['client_id'],
        ]);

        $this->session->flash('success', 'Orçamento criado com sucesso!');
        $this->redirect("/admin/orcamentos/{$budgetId}/editar");
    }

    /**
     * Visualiza orçamento
     */
    public function show(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('budgets.view');
        $db = Database::getInstance();
        $id = (int) $params['id'];

        $budget = $this->getBudgetWithDetails($db, $id);

        if (!$budget) {
            $this->redirect('/admin/orcamentos');
            return '';
        }

        return $this->adminView('budgets/show', [
            'title' => "Orçamento: {$budget['name']}",
            'budget' => $budget,
        ]);
    }

    /**
     * Formulário de edição
     */
    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('budgets.edit');
        $db = Database::getInstance();
        $id = (int) $params['id'];

        $budget = $this->getBudgetWithDetails($db, $id);

        if (!$budget) {
            $this->redirect('/admin/orcamentos');
            return '';
        }

        $clients = $db->fetchAll("SELECT id, name, company FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        $portfolios = $db->fetchAll("SELECT id, name, image_cover, slug FROM portfolios WHERE is_active = 1 ORDER BY sort_order");

        return $this->adminView('budgets/form', [
            'title' => "Editar: {$budget['name']}",
            'budget' => $budget,
            'clients' => $clients,
            'portfolios' => $portfolios,
            'settings' => $this->getBudgetSettings($db),
        ]);
    }

    /**
     * Atualiza orçamento
     */
    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('budgets.edit');
        $id = (int) $params['id'];

        $data = $this->validate([
            'name' => 'required|max:255',
            'client_id' => 'required|integer',
            'payment_type' => 'required|in:one_time,monthly,installments',
        ]);

        $db = Database::getInstance();

        $budgetData = [
            'name' => $data['name'],
            'client_id' => $data['client_id'],
            'status' => $request->input('status') ?? 'draft',
            'payment_type' => $data['payment_type'],
            'validity_date' => $request->input('validity_date') ?: null,
            'payment_pix' => $request->input('payment_pix') ? 1 : 0,
            'payment_card' => $request->input('payment_card') ? 1 : 0,
            'payment_boleto' => $request->input('payment_boleto') ? 1 : 0,
            'discount_percent' => (float) ($request->input('discount_percent') ?? 0),
            'minimum_entry' => $request->input('minimum_entry') ?: null,
            'installments' => (int) ($request->input('installments') ?? 1),
            'monthly_value' => $request->input('monthly_value') ?: null,
            'notes' => $request->input('notes'),
            'internal_notes' => $request->input('internal_notes'),
            'about_company' => $request->input('about_company'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Recalcula valores
        $blocks = $db->fetchAll("SELECT value FROM budget_blocks WHERE budget_id = :id", ['id' => $id]);
        $totalValue = array_sum(array_column($blocks, 'value'));

        $discountValue = $totalValue * ((float) $budgetData['discount_percent'] / 100);
        $finalValue = $totalValue - $discountValue;
        $installmentValue = $budgetData['installments'] > 0 ? $finalValue / $budgetData['installments'] : $finalValue;

        $budgetData['total_value'] = $totalValue;
        $budgetData['discount_value'] = $discountValue;
        $budgetData['final_value'] = $finalValue;
        $budgetData['installment_value'] = round($installmentValue, 2);

        $db->update('budgets', $budgetData, 'id = :id', ['id' => $id]);

        Logger::audit('Orçamento atualizado', ['budget_id' => $id]);

        $this->session->flash('success', 'Orçamento atualizado!');
        $this->redirect("/admin/orcamentos/{$id}/editar");
    }

    /**
     * Adiciona bloco ao orçamento
     */
    public function addBlock(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('budgets.edit');
        $budgetId = (int) $params['id'];

        $data = $this->validate([
            'title' => 'required|max:255',
        ]);

        $db = Database::getInstance();

        $maxOrder = $db->fetchOne("SELECT MAX(sort_order) as max_order FROM budget_blocks WHERE budget_id = :id", ['id' => $budgetId]);

        $blockData = [
            'budget_id' => $budgetId,
            'title' => $data['title'],
            'description' => $request->input('description'),
            'scope' => $request->input('scope'),
            'features' => $request->input('features'),
            'deadline' => $request->input('deadline'),
            'value' => (float) ($request->input('value') ?? 0),
            'notes' => $request->input('notes'),
            'sort_order' => ($maxOrder['max_order'] ?? 0) + 1,
            'requested_at' => $request->input('requested_at') ?: date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $db->insert('budget_blocks', $blockData);

        // Recalcula total do orçamento
        $this->recalculateBudget($db, $budgetId);

        $this->session->flash('success', 'Bloco adicionado!');
        $this->redirect("/admin/orcamentos/{$budgetId}/editar");
    }

    /**
     * Atualiza bloco
     */
    public function updateBlock(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('budgets.edit');
        $blockId = (int) $params['id'];

        $db = Database::getInstance();
        $block = $db->fetchOne("SELECT budget_id FROM budget_blocks WHERE id = :id", ['id' => $blockId]);

        if (!$block) {
            $this->response->error('Bloco não encontrado', 404);
            return;
        }

        $db->update('budget_blocks', [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'scope' => $request->input('scope'),
            'features' => $request->input('features'),
            'deadline' => $request->input('deadline'),
            'value' => (float) ($request->input('value') ?? 0),
            'notes' => $request->input('notes'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $blockId]);

        $this->recalculateBudget($db, (int) $block['budget_id']);

        if ($request->isAjax()) {
            $this->response->success(null, 'Bloco atualizado!');
        } else {
            $this->session->flash('success', 'Bloco atualizado!');
            $this->redirect("/admin/orcamentos/{$block['budget_id']}/editar");
        }
    }

    /**
     * Remove bloco
     */
    public function deleteBlock(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('budgets.edit');
        $blockId = (int) $params['id'];

        $db = Database::getInstance();
        $block = $db->fetchOne("SELECT budget_id FROM budget_blocks WHERE id = :id", ['id' => $blockId]);

        if ($block) {
            $db->delete('budget_blocks', 'id = :id', ['id' => $blockId]);
            $this->recalculateBudget($db, (int) $block['budget_id']);
        }

        if ($request->isAjax()) {
            $this->response->success(null, 'Bloco removido!');
        } else {
            $this->session->flash('success', 'Bloco removido!');
            $this->redirect("/admin/orcamentos/{$block['budget_id']}/editar");
        }
    }

    /**
     * Duplica orçamento
     */
    public function duplicate(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('budgets.create');
        $id = (int) $params['id'];
        $db = Database::getInstance();

        $budget = $db->fetchOne("SELECT * FROM budgets WHERE id = :id", ['id' => $id]);

        if (!$budget) {
            $this->redirect('/admin/orcamentos');
            return;
        }

        unset($budget['id']);
        $budget['hash'] = bin2hex(random_bytes(16));
        $budget['name'] = $budget['name'] . ' (Cópia)';
        $budget['status'] = 'draft';
        $budget['created_at'] = date('Y-m-d H:i:s');
        $budget['updated_at'] = date('Y-m-d H:i:s');
        $budget['sent_at'] = null;
        $budget['viewed_at'] = null;
        $budget['approved_at'] = null;
        $budget['rejected_at'] = null;

        $newId = $db->insert('budgets', $budget);

        // Duplica blocos
        $blocks = $db->fetchAll("SELECT * FROM budget_blocks WHERE budget_id = :id", ['id' => $id]);
        foreach ($blocks as $block) {
            unset($block['id']);
            $block['budget_id'] = $newId;
            $block['created_at'] = date('Y-m-d H:i:s');
            $block['updated_at'] = date('Y-m-d H:i:s');
            $db->insert('budget_blocks', $block);
        }

        $this->session->flash('success', 'Orçamento duplicado!');
        $this->redirect("/admin/orcamentos/{$newId}/editar");
    }

    /**
     * Exclui orçamento (soft delete)
     */
    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('budgets.delete');
        $id = (int) $params['id'];

        $db = Database::getInstance();
        $db->update('budgets', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);

        Logger::audit('Orçamento excluído', ['budget_id' => $id]);

        if ($request->isAjax()) {
            $this->response->success(null, 'Orçamento excluído!');
        } else {
            $this->session->flash('success', 'Orçamento excluído!');
            $this->redirect('/admin/orcamentos');
        }
    }

    // === Métodos auxiliares ===

    private function getBudgetWithDetails(Database $db, int $id): ?array
    {
        $budget = $db->fetchOne("
            SELECT b.*, c.name as client_name, c.company as client_company, c.email as client_email
            FROM budgets b
            LEFT JOIN clients c ON b.client_id = c.id
            WHERE b.id = :id AND b.deleted_at IS NULL
        ", ['id' => $id]);

        if (!$budget) {
            return null;
        }

        $budget['blocks'] = $db->fetchAll("
            SELECT * FROM budget_blocks WHERE budget_id = :id ORDER BY sort_order
        ", ['id' => $id]);

        return $budget;
    }

    private function recalculateBudget(Database $db, int $budgetId): void
    {
        $budget = $db->fetchOne("SELECT discount_percent, installments FROM budgets WHERE id = :id", ['id' => $budgetId]);
        $blocks = $db->fetchAll("SELECT value FROM budget_blocks WHERE budget_id = :id", ['id' => $budgetId]);

        $totalValue = array_sum(array_column($blocks, 'value'));
        $discountPercent = (float) ($budget['discount_percent'] ?? 0);
        $discountValue = $totalValue * ($discountPercent / 100);
        $finalValue = $totalValue - $discountValue;
        $installments = max(1, (int) ($budget['installments'] ?? 1));
        $installmentValue = round($finalValue / $installments, 2);

        $db->update('budgets', [
            'total_value' => $totalValue,
            'discount_value' => $discountValue,
            'final_value' => $finalValue,
            'installment_value' => $installmentValue,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $budgetId]);
    }

    private function getBudgetSettings(Database $db): array
    {
        $settings = $db->fetchAll("SELECT `key`, value FROM settings WHERE `group` = 'budget'");
        $result = [];
        foreach ($settings as $s) {
            $result[$s['key']] = $s['value'];
        }
        return $result;
    }
}
