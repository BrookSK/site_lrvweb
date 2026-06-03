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
        return $this->adminView('ai-chat/index', ['title' => 'Assistente IA']);
    }

    public function send(Request $request, Response $response): void
    {
        $this->requireAuth();

        $message = $request->input('message') ?? '';
        $model = $request->input('model') ?? 'gpt-4';
        $history = json_decode($request->input('history') ?? '[]', true) ?: [];

        if (empty($message)) {
            $this->response->error('Mensagem vazia', 400);
            return;
        }

        $apiKey = Config::get('openai.api_key') ?: Config::setting('openai.api_key');
        if (empty($apiKey)) {
            $this->response->error('API Key não configurada. Vá em Configurações > Blog IA.', 400);
            return;
        }

        // Monta contexto da empresa
        $context = $this->buildContext();

        $messages = [
            ['role' => 'system', 'content' => $context],
        ];

        // Adiciona histórico
        foreach ($history as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $message];

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
            $usage = $result['usage'] ?? [];

            $this->response->success([
                'reply' => $reply,
                'model' => $model,
                'tokens' => $usage,
            ]);
        } catch (\Throwable $e) {
            Logger::error('Chat IA falhou', ['error' => $e->getMessage()]);
            $this->response->error('Erro: ' . $e->getMessage(), 500);
        }
    }

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
            $this->response->error('Erro ao transcrever (HTTP ' . $httpCode . ')', 500);
        }
    }

    private function buildContext(): string
    {
        $db = Database::getInstance();

        $clientCount = $db->fetchOne("SELECT COUNT(*) as t FROM clients WHERE is_active = 1 AND deleted_at IS NULL")['t'] ?? 0;
        $projectCount = $db->fetchOne("SELECT COUNT(*) as t FROM projects WHERE deleted_at IS NULL")['t'] ?? 0;
        $budgetCount = $db->fetchOne("SELECT COUNT(*) as t FROM budgets WHERE deleted_at IS NULL")['t'] ?? 0;

        $recentClients = $db->fetchAll("SELECT name, company, email FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 10");
        $recentProjects = $db->fetchAll("SELECT name, status, value FROM projects WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 10");
        $recentBudgets = $db->fetchAll("SELECT name, status, final_value FROM budgets WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 10");

        $clientsList = implode("\n", array_map(fn($c) => "- {$c['name']}" . ($c['company'] ? " ({$c['company']})" : ''), $recentClients));
        $projectsList = implode("\n", array_map(fn($p) => "- {$p['name']} [Status: {$p['status']}]" . ($p['value'] ? " R$ " . number_format((float)$p['value'], 2, ',', '.') : ''), $recentProjects));
        $budgetsList = implode("\n", array_map(fn($b) => "- {$b['name']} [Status: {$b['status']}] R$ " . number_format((float)($b['final_value'] ?? 0), 2, ',', '.'), $recentBudgets));

        return "Você é o assistente IA da LRV Web, uma empresa de tecnologia especializada em:
- Hospedagem Cloud & VPS (LRV Cloud Manager)
- Desenvolvimento de Sites e Sistemas Sob Medida
- E-commerce, Automação WhatsApp, Social Media

Dados atuais da empresa:
- {$clientCount} clientes ativos
- {$projectCount} projetos
- {$budgetCount} orçamentos

Últimos clientes:
{$clientsList}

Últimos projetos:
{$projectsList}

Últimos orçamentos:
{$budgetsList}

Você deve:
1. Ajudar com dúvidas sobre os projetos e clientes
2. Sugerir textos para orçamentos, e-mails, propostas
3. Ajudar com código PHP, JavaScript, CSS, SQL
4. Gerar conteúdo para blog e redes sociais
5. Responder em português brasileiro
6. Ser direto, profissional e útil

Responda em Markdown quando apropriado.";
    }
}
