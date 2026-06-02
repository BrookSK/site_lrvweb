<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class ClientDashboardController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $user = $this->getUser();

        if ($user['role'] !== 'cliente') {
            $this->redirect('/admin/dashboard');
            return '';
        }

        $db = Database::getInstance();
        $client = $db->fetchOne("SELECT * FROM clients WHERE user_id = :uid", ['uid' => $user['id']]);

        if (!$client) {
            $this->session->flash('error', 'Conta de cliente não encontrada.');
            $this->redirect('/login');
            return '';
        }

        $clientId = $client['id'];

        $projects = $db->fetchAll("SELECT * FROM projects WHERE client_id = :cid AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 5", ['cid' => $clientId]);
        $budgets = $db->fetchAll("SELECT * FROM budgets WHERE client_id = :cid AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 5", ['cid' => $clientId]);
        $invoices = $db->fetchAll("SELECT * FROM invoices WHERE client_id = :cid AND status = 'pending' ORDER BY due_date", ['cid' => $clientId]);
        $tickets = $db->fetchAll("SELECT * FROM tickets WHERE client_id = :cid AND status NOT IN ('closed', 'resolved') ORDER BY created_at DESC", ['cid' => $clientId]);

        return $this->view('client/dashboard', [
            'title' => 'Minha Área',
            'client' => $client,
            'projects' => $projects,
            'budgets' => $budgets,
            'invoices' => $invoices,
            'tickets' => $tickets,
            'user' => $user,
        ], 'client');
    }
}
