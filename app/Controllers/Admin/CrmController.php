<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class CrmController extends Controller
{
    private array $stages = [
        'lead' => 'Lead',
        'contacted' => 'Contato Realizado',
        'proposal_sent' => 'Proposta Enviada',
        'negotiation' => 'Negociação',
        'closed_won' => 'Fechado',
        'closed_lost' => 'Perdido',
    ];

    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('crm.view');
        return $this->kanban($request, $response);
    }

    public function kanban(Request $request, Response $response): string
    {
        $this->requirePermission('crm.view');
        $db = Database::getInstance();

        $leads = $db->fetchAll("
            SELECT l.*, u.name as responsible_name
            FROM crm_leads l
            LEFT JOIN users u ON l.responsible_id = u.id
            WHERE l.deleted_at IS NULL
            ORDER BY l.updated_at DESC
        ");

        $grouped = [];
        foreach ($this->stages as $key => $label) {
            $grouped[$key] = ['label' => $label, 'leads' => []];
        }
        foreach ($leads as $lead) {
            $grouped[$lead['stage']]['leads'][] = $lead;
        }

        $users = $db->fetchAll("SELECT id, name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");

        return $this->adminView('crm/kanban', [
            'title' => 'CRM - Funil de Vendas',
            'stages' => $grouped,
            'users' => $users,
        ]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('crm.manage');
        $data = $this->validate(['name' => 'required|max:150']);

        $db = Database::getInstance();
        $leadData = [
            'name' => $data['name'],
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'company' => $request->input('company'),
            'stage' => $request->input('stage') ?? 'lead',
            'source' => $request->input('source'),
            'responsible_id' => $request->input('responsible_id') ?: null,
            'value' => $request->input('value') ?: null,
            'notes' => $request->input('notes'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $id = $db->insert('crm_leads', $leadData);

        $db->insert('crm_lead_history', [
            'lead_id' => $id,
            'user_id' => $this->getUser()['id'],
            'action' => 'Lead criado',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Logger::audit('Lead criado', ['lead_id' => $id]);

        if ($request->isAjax()) {
            $this->response->success(['id' => $id], 'Lead criado!');
        } else {
            $this->session->flash('success', 'Lead criado!');
            $this->redirect('/admin/crm');
        }
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('crm.manage');
        $id = (int) $params['id'];
        $db = Database::getInstance();

        $db->update('crm_leads', [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'company' => $request->input('company'),
            'source' => $request->input('source'),
            'responsible_id' => $request->input('responsible_id') ?: null,
            'value' => $request->input('value') ?: null,
            'notes' => $request->input('notes'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);

        if ($request->isAjax()) {
            $this->response->success(null, 'Lead atualizado!');
        } else {
            $this->session->flash('success', 'Lead atualizado!');
            $this->redirect('/admin/crm');
        }
    }

    public function updateStatus(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('crm.manage');
        $id = (int) $params['id'];
        $newStage = $request->input('stage');
        $db = Database::getInstance();

        $lead = $db->fetchOne("SELECT stage FROM crm_leads WHERE id = :id", ['id' => $id]);
        if (!$lead) { $this->response->error('Lead não encontrado', 404); return; }

        $updateData = ['stage' => $newStage, 'updated_at' => date('Y-m-d H:i:s')];

        if ($newStage === 'closed_won' || $newStage === 'closed_lost') {
            $updateData['closed_at'] = date('Y-m-d H:i:s');
            if ($newStage === 'closed_lost') {
                $updateData['lost_reason'] = $request->input('lost_reason');
            }
        }

        $db->update('crm_leads', $updateData, 'id = :id', ['id' => $id]);

        $db->insert('crm_lead_history', [
            'lead_id' => $id,
            'user_id' => $this->getUser()['id'],
            'action' => "Status alterado: {$lead['stage']} → {$newStage}",
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->response->success(null, 'Status atualizado!');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('crm.manage');
        $id = (int) $params['id'];

        Database::getInstance()->update('crm_leads', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
        Logger::audit('Lead excluído', ['lead_id' => $id]);

        if ($request->isAjax()) {
            $this->response->success(null, 'Lead excluído!');
        } else {
            $this->session->flash('success', 'Lead excluído!');
            $this->redirect('/admin/crm');
        }
    }
}
