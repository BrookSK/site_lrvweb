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

use Core\Config;
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
            'project_id' => $request->input('project_id') ?: null,
            'hash' => $hash,
            'status' => 'draft',
            'payment_type' => $data['payment_type'],
            'validity_date' => $data['validity_date'] ?? null,
            'payment_pix' => $request->input('payment_pix') ? 1 : 0,
            'payment_card' => $request->input('payment_card') ? 1 : 0,
            'payment_boleto' => $request->input('payment_boleto') ? 1 : 0,
            'pix_discount_enabled' => $request->input('pix_discount_enabled') ? 1 : 0,
            'pix_discount_percent' => (float) ($request->input('pix_discount_percent') ?? 5),
            'discount_percent' => (float) ($request->input('discount_percent') ?? 0),
            'minimum_entry' => $request->input('minimum_entry') ?: null,
            'installments' => (int) ($request->input('installments') ?? 1),
            'notes' => $request->input('notes'),
            'internal_notes' => $request->input('internal_notes'),
            'ai_transcript' => $request->input('ai_transcript') ?: null,
            'about_company' => $request->input('about_company'),
            'created_by' => $user['id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $budgetId = $db->insert('budgets', $budgetData);

        // Salvar blocos enviados no formulário
        $blocks = $request->input('blocks');
        if (is_array($blocks)) {
            $sortOrder = 1;
            $totalValue = 0;
            foreach ($blocks as $blockInput) {
                if (empty($blockInput['title'])) continue;
                $blockValue = (float) ($blockInput['value'] ?? 0);
                $totalValue += $blockValue;

                $db->insert('budget_blocks', [
                    'budget_id' => $budgetId,
                    'title' => $blockInput['title'],
                    'description' => $blockInput['description'] ?? null,
                    'scope' => $blockInput['scope'] ?? null,
                    'features' => $blockInput['features'] ?? null,
                    'deadline' => $blockInput['deadline'] ?? null,
                    'value' => $blockValue,
                    'notes' => $blockInput['notes'] ?? null,
                    'sort_order' => $sortOrder++,
                    'requested_at' => $blockInput['requested_at'] ?? date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Recalcula totais
            $discountPercent = (float) ($request->input('discount_percent') ?? 0);
            $discountValue = $totalValue * ($discountPercent / 100);
            $finalValue = $totalValue - $discountValue;
            $installments = max(1, (int) ($request->input('installments') ?? 1));

            $db->update('budgets', [
                'total_value' => $totalValue,
                'discount_value' => $discountValue,
                'final_value' => $finalValue,
                'installment_value' => round($finalValue / $installments, 2),
            ], 'id = :id', ['id' => $budgetId]);
        }

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
            'project_id' => $request->input('project_id') ?: null,
            'status' => $request->input('status') ?? 'draft',
            'payment_type' => $data['payment_type'],
            'validity_date' => $request->input('validity_date') ?: null,
            'payment_pix' => $request->input('payment_pix') ? 1 : 0,
            'payment_card' => $request->input('payment_card') ? 1 : 0,
            'payment_boleto' => $request->input('payment_boleto') ? 1 : 0,
            'pix_discount_enabled' => $request->input('pix_discount_enabled') ? 1 : 0,
            'pix_discount_percent' => (float) ($request->input('pix_discount_percent') ?? 5),
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

        // Recriar blocos se enviados pelo formulário
        $blocksInput = $request->input('blocks');
        if (is_array($blocksInput)) {
            $db->delete('budget_blocks', 'budget_id = :id', ['id' => $id]);

            $sortOrder = 1;
            $totalValue = 0;
            foreach ($blocksInput as $blockData) {
                if (empty($blockData['title'])) continue;
                $blockValue = (float) ($blockData['value'] ?? 0);
                $totalValue += $blockValue;

                $db->insert('budget_blocks', [
                    'budget_id' => $id,
                    'title' => $blockData['title'],
                    'description' => $blockData['description'] ?? null,
                    'scope' => $blockData['scope'] ?? null,
                    'features' => $blockData['features'] ?? null,
                    'deadline' => $blockData['deadline'] ?? null,
                    'value' => $blockValue,
                    'notes' => $blockData['notes'] ?? null,
                    'sort_order' => $sortOrder++,
                    'requested_at' => $blockData['requested_at'] ?? date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $discountPercent = (float) ($request->input('discount_percent') ?? 0);
            $discountValue = $totalValue * ($discountPercent / 100);
            $finalValue = $totalValue - $discountValue;
            $installments = max(1, (int) ($request->input('installments') ?? 1));

            $db->update('budgets', [
                'total_value' => $totalValue,
                'discount_value' => $discountValue,
                'final_value' => $finalValue,
                'installment_value' => round($finalValue / $installments, 2),
            ], 'id = :id', ['id' => $id]);
        }

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

    /**
     * IA preenche campos do orçamento baseado na transcrição de voz.
     * Cria cliente e projeto automaticamente se não existirem.
     */
    public function aiAutofill(Request $request, Response $response): void
    {
        $this->requirePermission('budgets.create');

        $apiKey = Config::get('openai.api_key') ?: Config::setting('openai.api_key');
        if (empty($apiKey)) {
            $this->response->error('API Key do OpenAI não configurada', 400);
            return;
        }

        $model = Config::setting('openai.model') ?: Config::get('openai.model', 'gpt-4o');
        $transcript = $request->input('transcript') ?? '';

        // Se veio áudio, transcreve com Whisper
        $audioFile = $request->file('audio');
        if ($audioFile && $audioFile['error'] === UPLOAD_ERR_OK) {
            $ch = curl_init('https://api.openai.com/v1/audio/transcriptions');
            $cfile = new \CURLFile($audioFile['tmp_name'], $audioFile['type'] ?? 'audio/webm', 'recording.webm');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ['file' => $cfile, 'model' => 'whisper-1', 'language' => 'pt'],
                CURLOPT_HTTPHEADER => ["Authorization: Bearer {$apiKey}"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 60,
            ]);
            $whisperResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $whisperData = json_decode($whisperResponse, true);
                $transcript = $whisperData['text'] ?? '';
            } else {
                Logger::error('Whisper falhou no orçamento', ['http' => $httpCode, 'response' => $whisperResponse]);
                $this->response->error('Erro ao transcrever áudio (HTTP ' . $httpCode . ')', 500);
                return;
            }
        }

        if (empty($transcript)) {
            $this->response->error('Nenhum áudio ou transcrição recebida', 400);
            return;
        }

        $db = Database::getInstance();

        // Busca dados para contexto
        $clients = $db->fetchAll("SELECT id, name, company FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        $clientNames = array_map(fn($c) => $c['name'] . ($c['company'] ? ' (' . $c['company'] . ')' : ''), $clients);

        $projects = $db->fetchAll("SELECT id, name, client_id FROM projects WHERE deleted_at IS NULL ORDER BY name");
        $projectNames = array_map(fn($p) => $p['name'], $projects);

        $today = date('d/m/Y');
        $prompt = "Você é um especialista em propostas comerciais da LRV Web, uma empresa de desenvolvimento web/tecnologia. Você escreve orçamentos profissionais, detalhados e bem redigidos — no mesmo nível de uma proposta comercial elaborada manualmente por um consultor sênior.

Você recebe transcrições de áudio onde o dono da empresa descreve o orçamento de forma rápida e informal (muitas vezes dizendo 'encher linguiça', ou seja, pedindo para você detalhar e expandir profissionalmente). Seu trabalho é transformar essa fala em uma proposta COMPLETA e PROFISSIONAL.

Data de hoje: {$today}

## TRANSCRIÇÃO DO ÁUDIO:
\"{$transcript}\"

## CONTEXTO DO SISTEMA:
Clientes cadastrados (ID - Nome): " . implode(', ', array_map(fn($c) => $c['id'] . '-' . $c['name'] . ($c['company'] ? ' (' . $c['company'] . ')' : ''), $clients)) . "
Projetos cadastrados (ID - Nome): " . implode(', ', array_map(fn($p) => $p['id'] . '-' . $p['name'], $projects)) . "

## FORMATO DE SAÍDA:
Retorne APENAS um JSON válido (sem markdown, sem backticks) com esta estrutura:

{
  \"budget_name\": \"Nome comercial do orçamento\",
  \"client\": { \"name\": \"Nome da empresa/cliente\", \"existing_id\": null, \"is_new\": true },
  \"project\": { \"name\": \"Nome do projeto\", \"description\": \"Descrição profissional do projeto\", \"existing_id\": null, \"is_new\": true },
  \"payment\": {
    \"type\": \"one_time | monthly | installments\",
    \"pix\": true, \"card\": true, \"boleto\": true,
    \"installments\": 1,
    \"pix_discount_enabled\": false, \"pix_discount_percent\": 5,
    \"discount_percent\": 0,
    \"minimum_entry\": null
  },
  \"validity_date\": null,
  \"notes\": \"Observação geral do orçamento (ver instruções abaixo)\",
  \"blocks\": [
    {
      \"title\": \"Título do serviço\",
      \"description\": \"Descrição em texto corrido\",
      \"features\": \"Item 1\\nItem 2\\nItem 3\",
      \"deadline\": \"Prazo\",
      \"scope\": \"Escopo em texto corrido\",
      \"notes\": \"Observação da solicitação (delimitação de escopo)\",
      \"value\": 500.00
    }
  ]
}

## COMO PREENCHER CADA CAMPO (SIGA EXATAMENTE ESTE PADRÃO DE ESCRITA):

### notes (observação geral do orçamento):
Texto profissional sobre condições comerciais. Exemplo de estilo:
\"O valor apresentado já considera a condição comercial especial e diferenciada da parceria com a agência. O pagamento poderá ser realizado via Pix, cartão ou boleto, com entrada mínima de 50%. Este orçamento é válido até DD/MM/AAAA.\"
Adapte conforme o que foi dito (formas de pagamento, entrada, validade, desconto, parceria).

### Em cada bloco:
- **title**: Título profissional e específico. Ex: \"Criação de Landing Page Institucional – DMR Assessoria Imobiliária\".
- **description**: Texto corrido (NÃO lista), 2 a 4 frases, descrevendo profissionalmente o que será desenvolvido, para quem, e as principais características. Estilo de proposta comercial.
- **features**: Lista granular e detalhada (um item por linha, separados por \\n). Quebre em MUITOS itens específicos. Para uma landing page institucional, por exemplo, liste cada seção separadamente (seção inicial/hero, sobre a empresa, serviços, diferenciais, contato), formulário de contato, responsividade, testes, publicação, etc. Quanto mais granular e completo, melhor.
- **deadline**: Prazo. Se não mencionado, pode usar 'A definir conforme recebimento dos materiais necessários para o desenvolvimento.' ou estimar (Landing Page: 7-15 dias úteis, Site: 15-25 dias úteis, E-commerce: 30-45 dias úteis, Sistema: 45-60 dias úteis).
- **scope**: Texto corrido LONGO e detalhado (3 a 6 frases) descrevendo tecnicamente tudo que o projeto contempla. Deve reafirmar o que está incluído, mencionar responsividade, painel administrativo (quando aplicável), tecnologia usada, etc. Estilo formal de contrato/proposta.
- **notes** (observação da solicitação): SEMPRE delimite o que NÃO está incluído no escopo, protegendo contra expectativas extras. Exemplo de estilo:
\"O desenvolvimento será realizado com base nos materiais, informações e orientações fornecidos pelo cliente. Solicitações que ultrapassem o escopo apresentado, como novas páginas, integrações, funcionalidades específicas ou desenvolvimentos adicionais, poderão ser avaliadas e orçadas separadamente.\"
- **value**: Valor numérico do bloco.

## REGRAS DE NEGÓCIO:
- Cliente/projeto existente na lista → preencha existing_id (ID numérico) e is_new = false. Senão → is_new = true e preencha name.
- Se falou 'já é com desconto'/'valor com desconto'/'condição especial', discount_percent = 0 (o valor já é o final).
- 'entrada mínima de 50%' → minimum_entry = 50.
- Mencionou desconto no PIX → pix_discount_enabled = true + porcentagem.
- Mencionou parcelas/vezes no cartão → installments = quantidade e type = 'installments'.
- 'válido até dia 12 do 10' → validity_date no formato YYYY-MM-DD (ex: 2026-10-12). Use o ano atual ou próximo mais lógico.
- Valores numéricos puros (sem R\$, sem ponto de milhar). Interprete coloquialismos: '400 conto' = 400, 'oitocentão' = 800, 'um e meio' = 1500, '500 pila' = 500.

## MÚLTIPLOS BLOCOS:
Crie um bloco SEPARADO para cada serviço distinto. Ex: se mencionou o site E a hospedagem, são 2 blocos. Para hospedagem/manutenção recorrente, deadline = 'Serviço recorrente mensal.' e detalhe os planos (ex: Hospedagem R\$65/mês, Hospedagem + Manutenção R\$90/mês, hora adicional R\$50) na description, features, scope e notes.

## CORREÇÃO DE TERMOS TÉCNICOS (a transcrição vem de áudio e erra muito):
'lending page'/'lendim page' → 'Landing Page'; 'uebsite' → 'Website'; 'ecomerce'/'e-comerce' → 'E-commerce'; 'osti'/'hosting' → 'Hospedagem'; 'dominio' → 'Domínio'; 'uordpress'/'wordpress' → 'WordPress'; 'elementor' → 'Elementor'; 'iu ai' → 'UI'; 'iu equis' → 'UX'; 'esse e o'/'seo' → 'SEO'; 'frontend' → 'Front-end'; 'backend' → 'Back-end'. Use SEMPRE a grafia correta.

## EXEMPLO DE REFERÊNCIA (siga ESTE nível de detalhe e redação):
Para 'landing page institucional feita direto na programação, com painel de login, R\$500' você deve gerar um bloco assim:
- title: \"Criação de Landing Page Institucional – [Empresa]\"
- description: \"Desenvolvimento de uma landing page institucional personalizada para a [Empresa], desenvolvida diretamente em programação e estruturada de acordo com a identidade visual da marca, com painel administrativo para gerenciamento e configurações básicas do projeto.\"
- features: \"Desenvolvimento da landing page diretamente em programação\\nCriação da estrutura visual seguindo a identidade da marca\\nCriação da seção inicial (banner/hero)\\nCriação da seção sobre a empresa\\nCriação da seção de serviços\\nCriação da seção de diferenciais\\nCriação da seção de atuação da empresa\\nCriação da seção de contato\\nImplementação de formulário de contato\\nConfiguração do envio de e-mails\\nCriação de painel administrativo com acesso por login\\nAdequação responsiva para computadores, tablets e celulares\\nTestes e ajustes finais\\nPublicação da landing page\"
- scope: \"O projeto contempla o desenvolvimento de uma landing page institucional personalizada para a [Empresa], construída diretamente em programação, sem utilização de WordPress. A página será estruturada de acordo com a identidade visual da marca e contará com seções institucionais, apresentação dos serviços, diferenciais e canais de contato. O projeto também contempla a criação de um painel administrativo com acesso por login, destinado às configurações e gerenciamento básico da landing page, incluindo configurações relacionadas ao envio de e-mails. A página será desenvolvida de forma responsiva, garantindo adequada visualização em computadores, tablets e dispositivos móveis.\"
- notes: \"O desenvolvimento será realizado com base nos materiais, informações e orientações fornecidos pelo cliente. Solicitações que ultrapassem o escopo apresentado, incluindo novas páginas, integrações, funcionalidades específicas ou desenvolvimentos adicionais, poderão ser avaliadas e orçadas separadamente.\"

IMPORTANTE: a LRV Web desenvolve sites/landing pages DIRETAMENTE EM PROGRAMAÇÃO (não WordPress), e SEMPRE cria um painel administrativo com login para configurações (envio de e-mail, etc.), mesmo em landing pages — a menos que o áudio diga explicitamente que é WordPress/Elementor.";

        try {
            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um gerente de projetos sênior e especialista em propostas comerciais de tecnologia. Interprete transcrições de áudio com inteligência, enriqueça com detalhes profissionais e retorne JSON válido puro (sem markdown, sem backticks, sem explicações). Seja criativo nas features e descrições — como se estivesse escrevendo uma proposta real para fechar negócio.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.6,
                    'max_tokens' => 4000,
                ]),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json', "Authorization: Bearer {$apiKey}"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 60,
            ]);

            $aiResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                $error = json_decode($aiResponse, true);
                throw new \RuntimeException($error['error']['message'] ?? "OpenAI HTTP {$httpCode}");
            }

            $result = json_decode($aiResponse, true);
            $content = $result['choices'][0]['message']['content'] ?? '';
            $content = preg_replace('/^```json\s*|\s*```$/m', '', trim($content));
            $data = json_decode($content, true);

            if (!$data || !isset($data['blocks'])) {
                throw new \RuntimeException('Resposta inválida da IA: ' . mb_substr($content, 0, 200));
            }

            // === Auto-criar cliente se necessário ===
            $clientId = null;
            if (!empty($data['client'])) {
                if (!empty($data['client']['existing_id'])) {
                    $clientId = (int) $data['client']['existing_id'];
                } elseif (!empty($data['client']['name'])) {
                    // Tenta encontrar por nome similar
                    $clientName = $data['client']['name'];
                    $found = $db->fetchOne(
                        "SELECT id FROM clients WHERE (name LIKE :name1 OR company LIKE :name2) AND is_active = 1 AND deleted_at IS NULL LIMIT 1",
                        ['name1' => '%' . $clientName . '%', 'name2' => '%' . $clientName . '%']
                    );

                    if ($found) {
                        $clientId = (int) $found['id'];
                    } elseif (!empty($data['client']['is_new'])) {
                        // Cria novo cliente
                        $clientId = $db->insert('clients', [
                            'name' => $clientName,
                            'company' => $clientName,
                            'is_active' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        $data['client_created'] = true;
                        $data['client_created_id'] = $clientId;
                    }
                }
            }
            $data['resolved_client_id'] = $clientId;

            // === Auto-criar projeto se necessário ===
            $projectId = null;
            if (!empty($data['project'])) {
                if (!empty($data['project']['existing_id'])) {
                    $projectId = (int) $data['project']['existing_id'];
                } elseif (!empty($data['project']['name']) && $clientId) {
                    // Tenta encontrar por nome similar
                    $projectName = $data['project']['name'];
                    $found = $db->fetchOne(
                        "SELECT id FROM projects WHERE name LIKE :name AND deleted_at IS NULL LIMIT 1",
                        ['name' => '%' . $projectName . '%']
                    );

                    if ($found) {
                        $projectId = (int) $found['id'];
                    } elseif (!empty($data['project']['is_new'])) {
                        // Cria novo projeto
                        $projectId = $db->insert('projects', [
                            'client_id' => $clientId,
                            'name' => $projectName,
                            'description' => $data['project']['description'] ?? null,
                            'status' => 'planning',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        $data['project_created'] = true;
                        $data['project_created_id'] = $projectId;
                    }
                }
            }
            $data['resolved_project_id'] = $projectId;

            $data['transcript'] = $transcript;

            Logger::audit('Orçamento IA preenchido via voz', [
                'client_id' => $clientId,
                'project_id' => $projectId,
                'client_created' => $data['client_created'] ?? false,
                'project_created' => $data['project_created'] ?? false,
            ]);

            $this->response->success($data, 'Orçamento preenchido pela IA');
        } catch (\Throwable $e) {
            Logger::error('IA Orçamento falhou', ['error' => $e->getMessage()]);
            $this->response->error('Erro: ' . $e->getMessage(), 500);
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
