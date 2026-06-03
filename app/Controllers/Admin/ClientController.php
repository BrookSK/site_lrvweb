<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class ClientController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('clients.view');
        $db = Database::getInstance();
        $search = $request->query('search');

        $where = 'deleted_at IS NULL';
        $params = [];

        if ($search) {
            $where .= ' AND (name LIKE :search OR email LIKE :search OR company LIKE :search)';
            $params['search'] = "%{$search}%";
        }

        $clients = $db->fetchAll("SELECT * FROM clients WHERE {$where} ORDER BY created_at DESC", $params);

        return $this->adminView('clients/index', [
            'title' => 'Clientes',
            'clients' => $clients,
            'search' => $search,
        ]);
    }

    public function create(Request $request, Response $response): string
    {
        $this->requirePermission('clients.create');

        return $this->adminView('clients/form', [
            'title' => 'Novo Cliente',
            'client' => null,
        ]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('clients.create');

        $data = $this->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|unique:clients,email',
        ]);

        $db = Database::getInstance();
        $fields = [
            'name', 'company', 'email', 'phone', 'whatsapp', 'cpf_cnpj',
            'address_street', 'address_number', 'address_complement',
            'address_neighborhood', 'address_city', 'address_state', 'address_zip',
            'website', 'social_instagram', 'social_facebook', 'social_linkedin', 'notes',
        ];

        $clientData = ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
        foreach ($fields as $field) {
            $clientData[$field] = $request->input($field) ?? '';
        }

        $id = $db->insert('clients', $clientData);

        // Cria usuário vinculado para o cliente poder logar
        $password = $request->input('client_password');
        if (!empty($password)) {
            $clientRoleId = $db->fetchOne("SELECT id FROM roles WHERE name = 'cliente'");
            if ($clientRoleId) {
                $userId = $db->insert('users', [
                    'name' => $clientData['name'],
                    'email' => $clientData['email'],
                    'password' => password_hash($password, PASSWORD_ARGON2ID),
                    'role_id' => (int) $clientRoleId['id'],
                    'phone' => $clientData['phone'] ?? null,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // Vincula o usuário ao cliente
                $db->update('clients', ['user_id' => $userId], 'id = :id', ['id' => $id]);
            }
        }

        Logger::audit('Cliente criado', ['client_id' => $id]);

        $this->session->flash('success', 'Cliente cadastrado com sucesso!');
        $this->redirect("/admin/clientes/{$id}");
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('clients.view');
        $db = Database::getInstance();
        $id = (int) $params['id'];

        $client = $db->fetchOne("SELECT * FROM clients WHERE id = :id AND deleted_at IS NULL", ['id' => $id]);
        if (!$client) { $this->redirect('/admin/clientes'); return ''; }

        $projects = $db->fetchAll("SELECT * FROM projects WHERE client_id = :id AND deleted_at IS NULL ORDER BY created_at DESC", ['id' => $id]);
        $budgets = $db->fetchAll("SELECT * FROM budgets WHERE client_id = :id AND deleted_at IS NULL ORDER BY created_at DESC", ['id' => $id]);
        $invoices = $db->fetchAll("SELECT * FROM invoices WHERE client_id = :id ORDER BY due_date DESC", ['id' => $id]);
        $documents = $db->fetchAll("SELECT * FROM documents WHERE client_id = :id AND deleted_at IS NULL ORDER BY created_at DESC", ['id' => $id]);

        return $this->adminView('clients/show', [
            'title' => $client['name'],
            'client' => $client,
            'projects' => $projects,
            'budgets' => $budgets,
            'invoices' => $invoices,
            'documents' => $documents,
        ]);
    }

    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('clients.edit');
        $db = Database::getInstance();
        $client = $db->fetchOne("SELECT * FROM clients WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$client) { $this->redirect('/admin/clientes'); return ''; }

        return $this->adminView('clients/form', [
            'title' => 'Editar: ' . $client['name'],
            'client' => $client,
        ]);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('clients.edit');
        $id = (int) $params['id'];

        $this->validate([
            'name' => 'required|max:150',
            'email' => "required|email|unique:clients,email,{$id}",
        ]);

        $db = Database::getInstance();
        $fields = [
            'name', 'company', 'email', 'phone', 'whatsapp', 'cpf_cnpj',
            'address_street', 'address_number', 'address_complement',
            'address_neighborhood', 'address_city', 'address_state', 'address_zip',
            'website', 'social_instagram', 'social_facebook', 'social_linkedin', 'notes', 'is_active',
        ];

        $clientData = ['updated_at' => date('Y-m-d H:i:s')];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value !== null) $clientData[$field] = $value;
        }

        $db->update('clients', $clientData, 'id = :id', ['id' => $id]);

        // Atualiza senha do usuário vinculado (se informou nova senha)
        $password = $request->input('client_password');
        if (!empty($password)) {
            $client = $db->fetchOne("SELECT user_id, email, name FROM clients WHERE id = :id", ['id' => $id]);

            if ($client['user_id']) {
                // Atualiza senha do usuário existente
                $db->update('users', [
                    'password' => password_hash($password, PASSWORD_ARGON2ID),
                    'updated_at' => date('Y-m-d H:i:s'),
                ], 'id = :id', ['id' => $client['user_id']]);
            } else {
                // Cria usuário se não existir
                $clientRoleId = $db->fetchOne("SELECT id FROM roles WHERE name = 'cliente'");
                if ($clientRoleId) {
                    $userId = $db->insert('users', [
                        'name' => $clientData['name'] ?? $client['name'],
                        'email' => $clientData['email'] ?? $client['email'],
                        'password' => password_hash($password, PASSWORD_ARGON2ID),
                        'role_id' => (int) $clientRoleId['id'],
                        'is_active' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $db->update('clients', ['user_id' => $userId], 'id = :id', ['id' => $id]);
                }
            }
        }

        Logger::audit('Cliente atualizado', ['client_id' => $id]);

        $this->session->flash('success', 'Cliente atualizado!');
        $this->redirect("/admin/clientes/{$id}");
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('clients.delete');
        $id = (int) $params['id'];

        Database::getInstance()->update('clients', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
        Logger::audit('Cliente excluído', ['client_id' => $id]);

        $this->session->flash('success', 'Cliente excluído!');
        $this->redirect('/admin/clientes');
    }
}
