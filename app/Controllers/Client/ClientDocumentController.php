<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class ClientDocumentController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        if (!$clientId) { $this->redirect('/login'); return ''; }

        $documents = Database::getInstance()->fetchAll(
            "SELECT * FROM documents WHERE client_id = :cid AND deleted_at IS NULL AND (is_public = 1 OR category IN ('contract', 'proposal')) ORDER BY created_at DESC",
            ['cid' => $clientId]
        );
        return $this->view('client/documents/index', ['title' => 'Meus Documentos', 'documents' => $documents, 'user' => $this->getUser()], 'client');
    }

    public function download(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $doc = Database::getInstance()->fetchOne("SELECT * FROM documents WHERE id = :id AND client_id = :cid AND deleted_at IS NULL", ['id' => (int) $params['id'], 'cid' => $clientId]);
        if (!$doc) { $this->response->error('Não encontrado', 404); return; }
        $this->response->download(ROOT_PATH . '/' . $doc['path'], $doc['original_name']);
    }

    private function getClientId(): ?int
    {
        $client = Database::getInstance()->fetchOne("SELECT id FROM clients WHERE user_id = :uid", ['uid' => $this->getUser()['id']]);
        return $client ? (int) $client['id'] : null;
    }
}
