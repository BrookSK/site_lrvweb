<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Config;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class PortfolioController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('portfolio.view');
        $portfolios = Database::getInstance()->fetchAll("SELECT p.*, pc.name as category_name FROM portfolios p LEFT JOIN portfolio_categories pc ON p.category_id = pc.id ORDER BY p.sort_order, p.created_at DESC");
        return $this->adminView('portfolio/index', ['title' => 'Portfólio', 'portfolios' => $portfolios]);
    }

    public function create(Request $request, Response $response): string
    {
        $this->requirePermission('portfolio.manage');
        $db = Database::getInstance();
        $categories = $db->fetchAll("SELECT * FROM portfolio_categories ORDER BY sort_order");
        $clients = $db->fetchAll("SELECT id, name FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        return $this->adminView('portfolio/form', ['title' => 'Novo Projeto', 'portfolio' => null, 'categories' => $categories, 'clients' => $clients]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('portfolio.manage');
        $data = $this->validate(['name' => 'required|max:200']);
        $db = Database::getInstance();

        $slug = $this->createSlug($data['name']);

        $portfolioData = [
            'name' => $data['name'],
            'slug' => $slug,
            'category_id' => $request->input('category_id') ?: null,
            'client_id' => $request->input('client_id') ?: null,
            'description' => $request->raw('description'),
            'technologies' => $request->input('technologies'),
            'url' => $request->input('url'),
            'is_featured' => (int) ($request->input('is_featured') ?? 0),
            'is_active' => 1,
            'completed_at' => $request->input('completed_at') ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Upload da imagem de capa
        $file = $request->file('image_cover');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $portfolioData['image_cover'] = $this->uploadImage($file, 'portfolio');
        }

        $db->insert('portfolios', $portfolioData);
        Logger::audit('Portfolio criado', ['name' => $data['name']]);
        $this->session->flash('success', 'Projeto adicionado ao portfólio!');
        $this->redirect('/admin/portfolio');
    }

    public function edit(Request $request, Response $response, array $params): string
    {
        $this->requirePermission('portfolio.manage');
        $db = Database::getInstance();
        $portfolio = $db->fetchOne("SELECT * FROM portfolios WHERE id = :id", ['id' => (int) $params['id']]);
        if (!$portfolio) { $this->redirect('/admin/portfolio'); return ''; }
        $categories = $db->fetchAll("SELECT * FROM portfolio_categories ORDER BY sort_order");
        $clients = $db->fetchAll("SELECT id, name FROM clients WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name");
        return $this->adminView('portfolio/form', ['title' => 'Editar: ' . $portfolio['name'], 'portfolio' => $portfolio, 'categories' => $categories, 'clients' => $clients]);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('portfolio.manage');
        $id = (int) $params['id'];
        $db = Database::getInstance();

        $updateData = [
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id') ?: null,
            'client_id' => $request->input('client_id') ?: null,
            'description' => $request->raw('description'),
            'technologies' => $request->input('technologies'),
            'url' => $request->input('url'),
            'is_featured' => (int) ($request->input('is_featured') ?? 0),
            'is_active' => (int) ($request->input('is_active') ?? 1),
            'completed_at' => $request->input('completed_at') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $file = $request->file('image_cover');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $updateData['image_cover'] = $this->uploadImage($file, 'portfolio');
        }

        $db->update('portfolios', $updateData, 'id = :id', ['id' => $id]);
        $this->session->flash('success', 'Portfólio atualizado!');
        $this->redirect('/admin/portfolio');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('portfolio.manage');
        Database::getInstance()->delete('portfolios', 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Item removido do portfólio!');
        $this->redirect('/admin/portfolio');
    }

    /**
     * IA preenche campos do portfólio baseado na transcrição de voz
     */
    public function aiAutofill(Request $request, Response $response): void
    {
        $this->requirePermission('portfolio.manage');

        $apiKey = Config::get('openai.api_key') ?: Config::setting('openai.api_key');
        if (empty($apiKey)) {
            $this->response->error('API Key do OpenAI não configurada', 400);
            return;
        }

        $model = Config::setting('openai.model') ?: Config::get('openai.model', 'gpt-4');
        $transcript = $request->input('transcript') ?? '';

        // Se veio áudio, transcreve com Whisper
        $audioFile = $request->file('audio');
        if ($audioFile && $audioFile['error'] === UPLOAD_ERR_OK) {
            // Envia para Whisper
            $ch = curl_init('https://api.openai.com/v1/audio/transcriptions');
            $cfile = new \CURLFile($audioFile['tmp_name'], $audioFile['type'] ?? 'audio/webm', 'recording.webm');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'file' => $cfile,
                    'model' => 'whisper-1',
                    'language' => 'pt',
                ],
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
                Logger::error('Whisper falhou', ['http' => $httpCode, 'response' => $whisperResponse]);
                $this->response->error('Erro ao transcrever áudio (HTTP ' . $httpCode . ')', 500);
                return;
            }
        }

        if (empty($transcript)) {
            $this->response->error('Nenhum áudio ou transcrição recebida', 400);
            return;
        }

        // Envia transcrição pro GPT para preencher campos
        $prompt = "Baseado na seguinte descrição falada de um projeto de portfólio de uma empresa de tecnologia/web, extraia e melhore as informações.

Transcrição: \"{$transcript}\"

Categorias disponíveis: Site, Sistema, E-commerce, Hospedagem, Social Media, Automação
Clientes cadastrados: " . implode(', ', array_map(fn($c) => $c['name'], Database::getInstance()->fetchAll("SELECT name FROM clients WHERE is_active = 1 AND deleted_at IS NULL"))) . "

Retorne APENAS um JSON válido com estas chaves:
- name: Nome curto e profissional do projeto (max 80 caracteres)
- description: Descrição LONGA e detalhada do projeto em HTML. Deve ter pelo menos 3-4 parágrafos usando tags <p>, <h3>, <ul>, <li>, <strong>. Inclua: o que foi feito, desafios superados, tecnologias utilizadas, funcionalidades principais, resultados alcançados e benefícios para o cliente. Escreva de forma persuasiva e profissional, como um case de sucesso. Mínimo 200 palavras.
- technologies: Lista de tecnologias separadas por vírgula
- url: URL do projeto se mencionada (ou string vazia)
- category: Uma das categorias disponíveis que mais se encaixa (ex: 'Site', 'Sistema', 'E-commerce'). Se não conseguir identificar, use string vazia.
- client: Nome do cliente se mencionado (deve ser exatamente igual a um dos cadastrados). Se não mencionou, string vazia.
- completed_at: Data de conclusão no formato YYYY-MM-DD se mencionada (ex: se disse 'janeiro de 2025' retorne '2025-01-01'). Se não mencionou, string vazia.

Melhore o texto para ficar profissional, como portfólio de empresa de desenvolvimento web.";

        try {
            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um copywriter de portfólios de tecnologia. Sempre responda em JSON válido.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1500,
                ]),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json', "Authorization: Bearer {$apiKey}"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
            ]);

            $aiResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) throw new \RuntimeException("OpenAI GPT error (HTTP {$httpCode})");

            $result = json_decode($aiResponse, true);
            $content = $result['choices'][0]['message']['content'] ?? '';
            $content = preg_replace('/^```json\s*|\s*```$/m', '', trim($content));
            $data = json_decode($content, true);

            if (!$data || !isset($data['name'])) throw new \RuntimeException('Resposta inválida da IA');

            $data['transcript'] = $transcript;
            $this->response->success($data, 'Campos preenchidos');
        } catch (\Throwable $e) {
            Logger::error('IA Portfolio falhou', ['error' => $e->getMessage()]);
            $this->response->error('Erro: ' . $e->getMessage(), 500);
        }
    }

    private function uploadImage(array $file, string $folder): string
    {
        $uploadDir = ROOT_PATH . "/public/assets/img/{$folder}/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return "/assets/img/{$folder}/{$filename}";
    }

    private function createSlug(string $text): string
    {
        $slug = mb_strtolower($text);
        $slug = preg_replace('/[áàãâä]/u', 'a', $slug);
        $slug = preg_replace('/[éèêë]/u', 'e', $slug);
        $slug = preg_replace('/[íìîï]/u', 'i', $slug);
        $slug = preg_replace('/[óòõôö]/u', 'o', $slug);
        $slug = preg_replace('/[úùûü]/u', 'u', $slug);
        $slug = preg_replace('/[ç]/u', 'c', $slug);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        return trim($slug, '-');
    }
}
