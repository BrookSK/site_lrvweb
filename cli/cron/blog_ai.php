<?php

/**
 * Cron Job: Geração Automática de Posts via IA
 * 
 * Gera artigos automaticamente usando a API OpenAI.
 * Configurar no crontab conforme frequência desejada.
 * 
 * Exemplos crontab:
 *   Diário:  0 8 * * * php /path/cli/cron/blog_ai.php
 *   Semanal: 0 8 * * 1 php /path/cli/cron/blog_ai.php
 * 
 * @package CLI\Cron
 */

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__, 2));

require ROOT_PATH . '/vendor/autoload.php';

use Core\Config;

Config::load(ROOT_PATH . '/config/app.php');

define('APP_ENV', Config::get('app.env', 'production'));
define('APP_DEBUG', false);

// Verifica se está habilitado
$apiKey = Config::get('openai.api_key', '');
if (empty($apiKey)) {
    echo "[Blog AI] API Key não configurada. Abortando.\n";
    exit(0);
}

try {
    $db = \Core\Database::getInstance();

    // Verifica configurações
    $aiEnabled = Config::get('openai.blog_enabled', false);
    if (!$aiEnabled) {
        echo "[Blog AI] Geração automática desabilitada.\n";
        exit(0);
    }

    $languages = Config::get('openai.blog_languages', ['pt', 'en', 'es']);

    foreach ($languages as $lang) {
        echo "[Blog AI] Gerando artigo em '{$lang}'...\n";
        generateBlogPost($db, trim($lang));
    }

    echo "[Blog AI] Concluído com sucesso.\n";
} catch (\Throwable $e) {
    echo "[Blog AI] ERRO: " . $e->getMessage() . "\n";
    \Core\Logger::error('Blog AI Cron falhou', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
    exit(1);
}

/**
 * Gera um post utilizando OpenAI
 */
function generateBlogPost(\Core\Database $db, string $language): void
{
    $apiKey = Config::get('openai.api_key');
    $model = Config::get('openai.model', 'gpt-4');

    $langNames = ['pt' => 'Português Brasileiro', 'en' => 'English', 'es' => 'Español'];
    $langName = $langNames[$language] ?? 'Português Brasileiro';

    // Registra job
    $jobId = $db->insert('blog_ai_jobs', [
        'status' => 'processing',
        'language' => $language,
        'started_at' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
    ]);

    try {
        // Prompt para geração de conteúdo
        $prompt = "Gere um artigo de blog profissional sobre desenvolvimento web, hospedagem de sites ou marketing digital. 
        O artigo deve ser em {$langName}, ter entre 800-1200 palavras, e incluir:
        - Título atrativo (max 70 caracteres)
        - Resumo (max 160 caracteres) 
        - Conteúdo com subtítulos (H2, H3)
        - Meta description para SEO (max 155 caracteres)
        - 5 palavras-chave relevantes separadas por vírgula
        
        Retorne em formato JSON com as chaves: title, excerpt, content, meta_description, keywords";

        $response = callOpenAI($apiKey, $model, $prompt);

        if (!$response) {
            throw new \RuntimeException('Resposta vazia da API OpenAI');
        }

        $data = json_decode($response, true);
        if (!$data || !isset($data['title'])) {
            throw new \RuntimeException('Formato de resposta inválido');
        }

        // Cria o slug
        $slug = createSlug($data['title']) . '-' . date('Ymd');

        // Insere o post
        $postId = $db->insert('blog_posts', [
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? '',
            'content' => $data['content'] ?? '',
            'meta_title' => $data['title'],
            'meta_description' => $data['meta_description'] ?? '',
            'meta_keywords' => $data['keywords'] ?? '',
            'tags' => $data['keywords'] ?? '',
            'status' => 'published',
            'is_ai_generated' => 1,
            'language' => $language,
            'published_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Atualiza job
        $db->update('blog_ai_jobs', [
            'status' => 'completed',
            'post_id' => $postId,
            'generated_title' => $data['title'],
            'generated_content' => $data['content'],
            'generated_meta' => json_encode($data),
            'completed_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $jobId]);

        echo "  ✓ Post criado: {$data['title']}\n";
    } catch (\Throwable $e) {
        $db->update('blog_ai_jobs', [
            'status' => 'failed',
            'error_message' => $e->getMessage(),
            'completed_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $jobId]);

        echo "  ✗ Erro: {$e->getMessage()}\n";
    }
}

/**
 * Chama a API OpenAI
 */
function callOpenAI(string $apiKey, string $model, string $prompt): ?string
{
    $ch = curl_init('https://api.openai.com/v1/chat/completions');

    $payload = json_encode([
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => 'Você é um especialista em marketing digital e desenvolvimento web. Sempre responda em JSON válido.'],
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.7,
        'max_tokens' => 2000,
        'response_format' => ['type' => 'json_object'],
    ]);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            "Authorization: Bearer {$apiKey}",
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        throw new \RuntimeException("OpenAI API error (HTTP {$httpCode})");
    }

    $result = json_decode($response, true);
    return $result['choices'][0]['message']['content'] ?? null;
}

/**
 * Cria slug a partir do texto
 */
function createSlug(string $text): string
{
    $slug = mb_strtolower($text);
    $slug = preg_replace('/[áàãâä]/u', 'a', $slug);
    $slug = preg_replace('/[éèêë]/u', 'e', $slug);
    $slug = preg_replace('/[íìîï]/u', 'i', $slug);
    $slug = preg_replace('/[óòõôö]/u', 'o', $slug);
    $slug = preg_replace('/[úùûü]/u', 'u', $slug);
    $slug = preg_replace('/[ç]/u', 'c', $slug);
    $slug = preg_replace('/[ñ]/u', 'n', $slug);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}
