<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class ClientFinancialController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        if (!$clientId) { $this->redirect('/login'); return ''; }

        $invoices = Database::getInstance()->fetchAll("SELECT * FROM invoices WHERE client_id = :cid ORDER BY due_date DESC", ['cid' => $clientId]);
        return $this->view('client/financial/index', ['title' => 'Financeiro', 'invoices' => $invoices, 'user' => $this->getUser()], 'client');
    }

    public function invoices(Request $request, Response $response): string
    {
        return $this->index($request, $response);
    }

    private function getClientId(): ?int
    {
        $client = Database::getInstance()->fetchOne("SELECT id FROM clients WHERE user_id = :uid", ['uid' => $this->getUser()['id']]);
        return $client ? (int) $client['id'] : null;
    }
}
