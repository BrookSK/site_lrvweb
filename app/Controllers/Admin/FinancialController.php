<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class FinancialController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('financial.view');
        $db = Database::getInstance();

        $month = $request->query('month') ?? date('Y-m');
        [$year, $m] = explode('-', $month);

        $monthlyRevenue = $db->fetchOne("SELECT COALESCE(SUM(value), 0) as total FROM financial_entries WHERE type = 'revenue' AND DATE_FORMAT(date, '%Y-%m') = :month", ['month' => $month]);
        $monthlyExpense = $db->fetchOne("SELECT COALESCE(SUM(value), 0) as total FROM financial_entries WHERE type = 'expense' AND DATE_FORMAT(date, '%Y-%m') = :month", ['month' => $month]);
        $yearRevenue = $db->fetchOne("SELECT COALESCE(SUM(value), 0) as total FROM financial_entries WHERE type = 'revenue' AND YEAR(date) = :year", ['year' => $year]);
        $clientCount = $db->fetchOne("SELECT COUNT(DISTINCT client_id) as total FROM financial_entries WHERE type = 'revenue' AND DATE_FORMAT(date, '%Y-%m') = :month", ['month' => $month]);

        $stats = [
            'monthly_revenue' => (float) $monthlyRevenue['total'],
            'monthly_expense' => (float) $monthlyExpense['total'],
            'monthly_profit' => (float) $monthlyRevenue['total'] - (float) $monthlyExpense['total'],
            'year_revenue' => (float) $yearRevenue['total'],
            'ticket_medio' => $clientCount['total'] > 0 ? (float) $monthlyRevenue['total'] / (int) $clientCount['total'] : 0,
        ];

        $entries = $db->fetchAll("
            SELECT fe.*, c.name as client_name
            FROM financial_entries fe
            LEFT JOIN clients c ON fe.client_id = c.id
            WHERE DATE_FORMAT(fe.date, '%Y-%m') = :month
            ORDER BY fe.date DESC
        ", ['month' => $month]);

        return $this->adminView('financial/index', ['title' => 'Financeiro', 'stats' => $stats, 'entries' => $entries, 'month' => $month]);
    }

    public function revenues(Request $request, Response $response): string
    {
        $this->requirePermission('financial.view');
        $entries = Database::getInstance()->fetchAll("SELECT fe.*, c.name as client_name FROM financial_entries fe LEFT JOIN clients c ON fe.client_id = c.id WHERE fe.type = 'revenue' ORDER BY fe.date DESC LIMIT 100");
        return $this->adminView('financial/list', ['title' => 'Receitas', 'entries' => $entries, 'type' => 'revenue']);
    }

    public function expenses(Request $request, Response $response): string
    {
        $this->requirePermission('financial.view');
        $entries = Database::getInstance()->fetchAll("SELECT * FROM financial_entries WHERE type = 'expense' ORDER BY date DESC LIMIT 100");
        return $this->adminView('financial/list', ['title' => 'Despesas', 'entries' => $entries, 'type' => 'expense']);
    }

    public function cashFlow(Request $request, Response $response): string
    {
        $this->requirePermission('financial.view');
        $db = Database::getInstance();

        $months = $db->fetchAll("
            SELECT DATE_FORMAT(date, '%Y-%m') as month,
                   SUM(CASE WHEN type = 'revenue' THEN value ELSE 0 END) as revenue,
                   SUM(CASE WHEN type = 'expense' THEN value ELSE 0 END) as expense
            FROM financial_entries
            WHERE date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(date, '%Y-%m')
            ORDER BY month
        ");

        return $this->adminView('financial/cash-flow', ['title' => 'Fluxo de Caixa', 'months' => $months]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('financial.manage');
        $data = $this->validate(['description' => 'required', 'value' => 'required|numeric', 'date' => 'required|date', 'type' => 'required|in:revenue,expense']);

        $db = Database::getInstance();
        $isInstallment = $request->input('is_installment');
        $installments = (int) ($request->input('installments') ?? 1);
        $isRecurring = $request->input('is_recurring');

        $baseData = [
            'type' => $data['type'],
            'category' => $request->input('category') ?? 'outros',
            'client_id' => $request->input('client_id') ?: null,
            'project_id' => $request->input('project_id') ?: null,
            'is_recurring' => $isRecurring ? 1 : 0,
            'recurring_frequency' => $isRecurring ? ($request->input('recurring_frequency') ?? 'monthly') : null,
            'notes' => $request->input('notes'),
        ];

        if ($isInstallment && $installments > 1) {
            // Cria múltiplos lançamentos (um por parcela)
            $totalValue = (float) $data['value'];
            $perInstallment = round($totalValue / $installments, 2);
            $startDate = $data['date'];

            for ($i = 0; $i < $installments; $i++) {
                $date = date('Y-m-d', strtotime("+{$i} months", strtotime($startDate)));
                $desc = $data['description'] . " ({$i}+1/{$installments})";

                $db->insert('financial_entries', array_merge($baseData, [
                    'description' => str_replace('{$i}+1', ($i + 1), $desc),
                    'value' => $perInstallment,
                    'date' => $date,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]));
            }

            Logger::audit('Lançamento parcelado criado', ['type' => $data['type'], 'value' => $totalValue, 'installments' => $installments]);
            $this->session->flash('success', "Lançamento parcelado em {$installments}x criado!");
        } else {
            // Lançamento único
            $db->insert('financial_entries', array_merge($baseData, [
                'description' => $data['description'],
                'value' => (float) $data['value'],
                'date' => $data['date'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]));

            Logger::audit('Lançamento financeiro criado', ['type' => $data['type'], 'value' => $data['value']]);
            $this->session->flash('success', 'Lançamento registrado!');
        }

        $this->redirect('/admin/financeiro');
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('financial.manage');
        $id = (int) $params['id'];

        Database::getInstance()->update('financial_entries', [
            'description' => $request->input('description'),
            'value' => (float) $request->input('value'),
            'date' => $request->input('date'),
            'category' => $request->input('category'),
            'notes' => $request->input('notes'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);

        $this->session->flash('success', 'Lançamento atualizado!');
        $this->redirect('/admin/financeiro');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('financial.manage');
        Database::getInstance()->delete('financial_entries', 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Lançamento excluído!');
        $this->redirect('/admin/financeiro');
    }
}
