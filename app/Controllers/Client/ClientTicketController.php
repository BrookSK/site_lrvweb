<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class ClientTicketController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        if (!$clientId) { $this->redirect('/login'); return ''; }

        $tickets = Database::getInstance()->fetchAll("SELECT * FROM tickets WHERE client_id = :cid ORDER BY created_at DESC", ['cid' => $clientId]);
        return $this->view('client/tickets/index', ['title' => 'Chamados', 'tickets' => $tickets, 'user' => $this->getUser()], 'client');
    }

    public function create(Request $request, Response $response): string
    {
        $this->requireAuth();
        return $this->view('client/tickets/form', ['title' => 'Novo Chamado', 'user' => $this->getUser()], 'client');
    }

    public function store(Request $request, Response $response): void
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $data = $this->validate(['subject' => 'required|max:255', 'description' => 'required']);

        $db = Database::getInstance();
        $id = $db->insert('tickets', [
            'client_id' => $clientId,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'category' => $request->input('category'),
            'priority' => $request->input('priority') ?? 'medium',
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Logger::audit('Chamado aberto pelo cliente', ['ticket_id' => $id]);
        $this->session->flash('success', 'Chamado criado!');
        $this->redirect("/cliente/chamados/{$id}");
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $db = Database::getInstance();

        $ticket = $db->fetchOne("SELECT * FROM tickets WHERE id = :id AND client_id = :cid", ['id' => (int) $params['id'], 'cid' => $clientId]);
        if (!$ticket) { $this->redirect('/cliente/chamados'); return ''; }

        $replies = $db->fetchAll("SELECT tr.*, u.name as user_name FROM ticket_replies tr LEFT JOIN users u ON tr.user_id = u.id WHERE tr.ticket_id = :id AND tr.is_internal = 0 ORDER BY tr.created_at", ['id' => $ticket['id']]);
        return $this->view('client/tickets/show', ['title' => $ticket['subject'], 'ticket' => $ticket, 'replies' => $replies, 'user' => $this->getUser()], 'client');
    }

    public function reply(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $clientId = $this->getClientId();
        $ticketId = (int) $params['id'];
        $db = Database::getInstance();

        $ticket = $db->fetchOne("SELECT id FROM tickets WHERE id = :id AND client_id = :cid", ['id' => $ticketId, 'cid' => $clientId]);
        if (!$ticket) { $this->redirect('/cliente/chamados'); return; }

        $data = $this->validate(['message' => 'required']);

        $db->insert('ticket_replies', [
            'ticket_id' => $ticketId,
            'user_id' => $this->getUser()['id'],
            'message' => $data['message'],
            'is_internal' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->update('tickets', ['status' => 'open', 'updated_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $ticketId]);

        $this->session->flash('success', 'Resposta enviada!');
        $this->redirect("/cliente/chamados/{$ticketId}");
    }

    private function getClientId(): ?int
    {
        $client = Database::getInstance()->fetchOne("SELECT id FROM clients WHERE user_id = :uid", ['uid' => $this->getUser()['id']]);
        return $client ? (int) $client['id'] : null;
    }
}
