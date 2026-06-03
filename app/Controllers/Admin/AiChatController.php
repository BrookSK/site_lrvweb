<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Config;
use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class AiChatController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $db = Database::getInstance();
        $userId = $this->getUser()['id'];

        $chatId = $request->query('chat') ? (int) $request->query('chat') : null;

        // Lista de chats do usuário
        $chats = $db->fetchAll("
            SELECT c.*, p.name as project_name 
            FROM ai_chats c 
            LEFT JOIN projects p ON c.project_id = p.id 
            WHERE c.user_id = :uid 
            ORDER BY c.updated_at DESC
        ", ['uid' => $userId]);

        // Projetos para criar chat vinculado
        $projects = $db->fetchAll("SELECT id, name FROM projects WHERE deleted_at IS NULL ORDER BY name");

        // Mensagens do chat selecionado
        $messages = [];
        $currentChat = null;
        if ($chatId) {
            $currentChat = $db->fetchOne("SELECT * FROM ai_chats WHERE id = :id AND user_id = :uid", ['id' => $chatId, 'uid' => $userId]);
            if ($currentChat) {
                $messages = $db->fetchAll("SELECT role, content, created_at FROM ai_chat_messages WHERE chat_id = :id ORDER BY created_at", ['id' => $chatId]);
            }
        }

        return $this->adminView('ai-chat/index', [
            'title' => 'Assistente IA',
            'chats' => $chats,
            'projects' => $projects,
            'messages' => $messages,
            'currentChat' => $currentChat,
            'chatId' => $chatId,
        ]);
    }

    /**
     * Cria novo chat (geral ou vinculado a projeto)
     */
    public function createChat(Request $request, Response $response): void
    {
        $this->requireAuth();
        $db = Database::getInstance();
        $userId = $this->getUser()['id'];
        $projectId = $request->input('project_id') ?: null;
        $title = $request->input('title') ?? 'Nova Conversa';

        if ($projectId) {
            $project = $db->fetchOne("SELECT name FROM projects WHERE id = :id", ['id' => $projectId]);
            $title = $project ? 'Projeto: ' . $project['name'] : $title;
        }

        $chatId = $db->insert('ai_chats', [
            'user_id' => $userId,
            'project_id' => $projectId,
            'title' => $title,
            'model' => $request->input('model') ?? 'gpt-4o',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->redirect("/admin/ia?chat={$chatId}");
    }

    /**
     * Envia mensagem no chat
     */
    public function send(Request $request, Response $response): void
    {
        $this->requireAuth();

        $message = $request->input('message') ?? '';
        $chatId = (int) ($request->input('chat_id') ?? 0);
        $model = $request->input('model') ?? 'gpt-4o';

        if (empty($message)) {
            $this->response->error('Mensagem vazia', 400);
            return;
        }

        $apiKey = Config::get('openai.api_key') ?: Config::setting('openai.api_key');
        if (empty($apiKey)) {
            $this->response->error('API Key não configurada', 400);
            return;
        }

        $db = Database::getInstance();
        $userId = $this->getUser()['id'];

        // Se não tem chat, cria um novo
        if (!$chatId) {
            $chatId = $db->insert('ai_chats', [
                'user_id' => $userId,
                'title' => mb_substr($message, 0, 80),
                'model' => $model,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Verifica se chat pertence ao user
        $chat = $db->fetchOne("SELECT * FROM ai_chats WHERE id = :id AND user_id = :uid", ['id' => $chatId, 'uid' => $userId]);
        if (!$chat) {
            $this->response->error('Chat não encontrado', 404);
            return;
        }

        // Salva mensagem do usuário
        $db->insert('ai_chat_messages', ['chat_id' => $chatId, 'role' => 'user', 'content' => $message, 'created_at' => date('Y-m-d H:i:s')]);

        // Monta contexto
        $context = $this->buildContext($chat['project_id']);

        // Busca histórico completo do chat
        $history = $db->fetchAll("SELECT role, content FROM ai_chat_messages WHERE chat_id = :id ORDER BY created_at", ['id' => $chatId]);

        $messages = [['role' => 'system', 'content' => $context]];
        foreach ($history as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        try {
            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 4000,
                ]),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json', "Authorization: Bearer {$apiKey}"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
            ]);

            $aiResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                $error = json_decode($aiResponse, true);
                throw new \RuntimeException($error['error']['message'] ?? "HTTP {$httpCode}");
            }

            $result = json_decode($aiResponse, true);
            $reply = $result['choices'][0]['message']['content'] ?? 'Sem resposta.';

            // Salva resposta da IA
            $db->insert('ai_chat_messages', ['chat_id' => $chatId, 'role' => 'assistant', 'content' => $reply, 'created_at' => date('Y-m-d H:i:s')]);

            // Atualiza timestamp do chat
            $db->update('ai_chats', ['updated_at' => date('Y-m-d H:i:s'), 'model' => $model], 'id = :id', ['id' => $chatId]);

            $this->response->success(['reply' => $reply, 'chat_id' => $chatId]);
        } catch (\Throwable $e) {
            Logger::error('Chat IA falhou', ['error' => $e->getMessage()]);
            $this->response->error('Erro: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Transcreve áudio com Whisper
     */
    public function transcribe(Request $request, Response $response): void
    {
        $this->requireAuth();
        $apiKey = Config::get('openai.api_key') ?: Config::setting('openai.api_key');
        $audioFile = $request->file('audio');

        if (!$audioFile || $audioFile['error'] !== UPLOAD_ERR_OK) {
            $this->response->error('Nenhum áudio recebido', 400);
            return;
        }

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
            $data = json_decode($whisperResponse, true);
            $this->response->success(['text' => $data['text'] ?? '']);
        } else {
            $this->response->error('Erro ao transcrever', 500);
        }
    }

    /**
     * Exclui um chat
     */
    public function deleteChat(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $db = Database::getInstance();
        $chatId = (int) $params['id'];
        $userId = $this->getUser()['id'];

        $db->delete('ai_chats', 'id = :id AND user_id = :uid', ['id' => $chatId, 'uid' => $userId]);
        $this->redirect('/admin/ia');
    }

    /**
     * Constrói contexto completo baseado no projeto ou geral
     */
    private function buildContext(?int $projectId = null): string
    {
        $db = Database::getInstance();

        if ($projectId) {
            return $this->buildProjectContext($db, $projectId);
        }

        return $this->buildFullContext($db);
    }

    private function buildFullContext(Database $db): string
    {
        $clients = $db->fetchAll("SELECT name, company, email, phone FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        $projects = $db->fetchAll("SELECT name, status, value, start_date, due_date FROM projects WHERE deleted_at IS NULL ORDER BY created_at DESC");
        $budgets = $db->fetchAll("SELECT name, status, final_value, client_id FROM budgets WHERE deleted_at IS NULL ORDER BY created_at DESC");

        $clientsList = implode("\n", array_map(fn($c) => "- {$c['name']}" . ($c['company'] ? " ({$c['company']})" : '') . " | {$c['email']}", $clients));
        $projectsList = implode("\n", array_map(fn($p) => "- {$p['name']} [{$p['status']}]" . ($p['value'] ? " R$" . number_format((float)$p['value'], 2, ',', '.') : ''), $projects));
        $budgetsList = implode("\n", array_map(fn($b) => "- {$b['name']} [{$b['status']}] R$" . number_format((float)($b['final_value'] ?? 0), 2, ',', '.'), $budgets));

        return "Você é o assistente IA interno da LRV Web. Tem acesso COMPLETO a todos os dados da empresa.

SOBRE A EMPRESA:
A LRV Web é uma empresa de tecnologia especializada em Hospedagem Cloud/VPS (LRV Cloud Manager), Desenvolvimento de Sites, Sistemas Sob Medida, E-commerce e Automação.

TODOS OS CLIENTES ({$this->count($clients)}):
{$clientsList}

TODOS OS PROJETOS ({$this->count($projects)}):
{$projectsList}

TODOS OS ORÇAMENTOS ({$this->count($budgets)}):
{$budgetsList}

REGRAS:
1. Responda sempre em português brasileiro
2. Seja direto, profissional e útil
3. Pode ajudar com: textos comerciais, propostas, respostas a clientes, código, análises, resumos
4. Use Markdown para formatar (negrito, listas, código)
5. Se perguntarem sobre um cliente/projeto específico, use os dados acima";
    }

    private function buildProjectContext(Database $db, int $projectId): string
    {
        $project = $db->fetchOne("SELECT p.*, c.name as client_name, c.company, c.email as client_email, c.phone as client_phone, c.whatsapp as client_whatsapp, c.notes as client_notes FROM projects p LEFT JOIN clients c ON p.client_id = c.id WHERE p.id = :id", ['id' => $projectId]);

        if (!$project) return $this->buildFullContext($db);

        $tasks = $db->fetchAll("SELECT t.title, t.status, t.priority, t.description, t.due_date, u.name as assigned_name FROM project_tasks t LEFT JOIN users u ON t.assigned_to = u.id WHERE t.project_id = :id ORDER BY t.priority DESC, t.created_at", ['id' => $projectId]);
        $members = $db->fetchAll("SELECT u.name, u.email, u.position, pm.role FROM project_members pm JOIN users u ON pm.user_id = u.id WHERE pm.project_id = :id", ['id' => $projectId]);
        $budgets = $db->fetchAll("SELECT b.name, b.status, b.final_value, b.notes, b.payment_type, b.installments FROM budgets b WHERE b.client_id = :cid AND b.deleted_at IS NULL", ['cid' => $project['client_id']]);
        $budgetBlocks = $db->fetchAll("SELECT bb.title, bb.description, bb.features, bb.value, bb.deadline FROM budget_blocks bb JOIN budgets b ON bb.budget_id = b.id WHERE b.client_id = :cid AND b.deleted_at IS NULL", ['cid' => $project['client_id']]);
        $documents = $db->fetchAll("SELECT name, category, original_name FROM documents WHERE project_id = :id AND deleted_at IS NULL", ['id' => $projectId]);
        $financial = $db->fetchAll("SELECT description, type, value, date, category FROM financial_entries WHERE project_id = :id ORDER BY date DESC", ['id' => $projectId]);
        $leads = $db->fetchAll("SELECT name, stage, value, source, notes FROM crm_leads WHERE client_id = :cid AND deleted_at IS NULL", ['cid' => $project['client_id']]);

        $tasksList = implode("\n", array_map(function($t) { $resp = $t['assigned_name'] ?? 'Ninguém'; $prio = $t['priority']; $s = "- [{$t['status']}] {$t['title']} | Resp: {$resp} | Prioridade: {$prio}"; if ($t['due_date']) $s .= " | Prazo: {$t['due_date']}"; if ($t['description']) $s .= " | Desc: {$t['description']}"; return $s; }, $tasks));
        $membersList = implode("\n", array_map(function($m) { $pos = $m['position'] ?? $m['role'] ?? 'Membro'; return "- {$m['name']} ({$pos}) | {$m['email']}"; }, $members));
        $budgetsList = implode("\n", array_map(function($b) { $val = number_format((float)($b['final_value'] ?? 0), 2, ',', '.'); $s = "- {$b['name']} [{$b['status']}] R\${$val} | Pagamento: {$b['payment_type']}"; if ($b['notes']) $s .= " | Obs: {$b['notes']}"; return $s; }, $budgets));
        $blocksList = implode("\n", array_map(function($bl) { $val = number_format((float)$bl['value'], 2, ',', '.'); $s = "- {$bl['title']}: R\${$val}"; if ($bl['deadline']) $s .= " | Prazo: {$bl['deadline']}"; if ($bl['description']) $s .= " | {$bl['description']}"; return $s; }, $budgetBlocks));
        $docsList = implode("\n", array_map(fn($d) => "- {$d['name']} ({$d['category']})", $documents));
        $finList = implode("\n", array_map(function($f) { $val = number_format((float)$f['value'], 2, ',', '.'); return "- [{$f['type']}] {$f['description']} R\${$val} ({$f['date']}) Cat: {$f['category']}"; }, $financial));
        $leadsList = implode("\n", array_map(function($l) { $s = "- {$l['name']} [{$l['stage']}]"; if ($l['value']) $s .= " R$" . number_format((float)$l['value'], 2, ',', '.'); if ($l['source']) $s .= " | Origem: {$l['source']}"; if ($l['notes']) $s .= " | {$l['notes']}"; return $s; }, $leads));

        return "Você é o assistente IA da LRV Web com ACESSO TOTAL ao projeto e todos os dados relacionados.

═══ PROJETO ═══
Nome: {$project['name']}
Status: {$project['status']}
Valor: " . ($project['value'] ? 'R$ ' . number_format((float)$project['value'], 2, ',', '.') : 'Não definido') . "
Início: " . ($project['start_date'] ?? 'Não definido') . "
Prazo: " . ($project['due_date'] ?? 'Não definido') . "
Descrição: " . ($project['description'] ?? 'Sem descrição') . "

═══ CLIENTE ═══
Nome: {$project['client_name']}" . ($project['company'] ? " ({$project['company']})" : '') . "
E-mail: {$project['client_email']}
Telefone: " . ($project['client_phone'] ?? '—') . "
WhatsApp: " . ($project['client_whatsapp'] ?? '—') . "
" . ($project['client_notes'] ? "Observações do cliente: {$project['client_notes']}" : '') . "

═══ EQUIPE DO PROJETO ({$this->count($members)} membros) ═══
{$membersList}

═══ TAREFAS ({$this->count($tasks)}) ═══
{$tasksList}

═══ ORÇAMENTOS DO CLIENTE ({$this->count($budgets)}) ═══
{$budgetsList}

═══ BLOCOS DOS ORÇAMENTOS (escopo detalhado) ═══
{$blocksList}

═══ CRM / LEADS DO CLIENTE ({$this->count($leads)}) ═══
{$leadsList}

═══ DOCUMENTOS DO PROJETO ({$this->count($documents)}) ═══
{$docsList}

═══ FINANCEIRO DO PROJETO ═══
{$finList}

═══ REGRAS ═══
1. Você TEM ACESSO COMPLETO a tudo acima. Use para responder.
2. Responda sempre em português brasileiro
3. Seja direto, profissional e útil
4. Ajude com: comunicação com cliente, resumos de progresso, decisões técnicas, textos de e-mail, código, relatórios
5. Use Markdown (negrito, listas, código) quando apropriado
6. Se algo não foi informado nos dados acima, diga que não tem essa informação";
    }

    private function count(array $arr): int
    {
        return count($arr);
    }
}
