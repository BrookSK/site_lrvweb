<?php

/**
 * Controller de Orçamento Público
 * 
 * Exibe o orçamento em uma página pública, bonita e responsiva.
 * 
 * @package App\Controllers\Site
 */

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class BudgetPublicController extends Controller
{
    /**
     * Exibe orçamento público pelo hash
     */
    public function show(Request $request, Response $response, array $params): string
    {
        $hash = $params['hash'] ?? '';
        $db = Database::getInstance();

        $budget = $db->fetchOne("
            SELECT b.*, c.name as client_name, c.company as client_company
            FROM budgets b
            LEFT JOIN clients c ON b.client_id = c.id
            WHERE b.hash = :hash AND b.deleted_at IS NULL
        ", ['hash' => $hash]);

        if (!$budget) {
            return $this->view('errors/404', [], null);
        }

        // Marca como visualizado
        if ($budget['status'] === 'sent' && !$budget['viewed_at']) {
            $db->update('budgets', [
                'status' => 'viewed',
                'viewed_at' => date('Y-m-d H:i:s'),
            ], 'id = :id', ['id' => $budget['id']]);
        }

        // Blocos
        $blocks = $db->fetchAll("
            SELECT * FROM budget_blocks WHERE budget_id = :id ORDER BY sort_order
        ", ['id' => $budget['id']]);

        // Portfólios selecionados
        $portfolios = $db->fetchAll("
            SELECT p.name, p.slug, p.image_cover, p.url, pc.name as category
            FROM budget_portfolios bp
            JOIN portfolios p ON bp.portfolio_id = p.id
            LEFT JOIN portfolio_categories pc ON p.category_id = pc.id
            WHERE bp.budget_id = :id
        ", ['id' => $budget['id']]);

        // Settings da empresa
        $settings = [];
        $rows = $db->fetchAll("SELECT `key`, value FROM settings WHERE `group` IN ('general', 'branding', 'social', 'budget')");
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }

        return $this->view('site/budget-public', [
            'title' => "Orçamento - {$budget['name']}",
            'budget' => $budget,
            'blocks' => $blocks,
            'portfolios' => $portfolios,
            'settings' => $settings,
        ], null);
    }
}
