<?php

/**
 * Controller de Configurações (Admin)
 * 
 * Gerencia TODAS as configurações do sistema pelo painel.
 * Substitui o uso de .env - tudo é configurável pela interface.
 * 
 * Seções:
 * - Geral (nome, URL, idioma)
 * - Banco de Dados
 * - E-mail (SMTP)
 * - Branding (logos, favicon)
 * - SEO
 * - Redes Sociais
 * - Segurança
 * - Blog IA (OpenAI)
 * - Backup
 * - Orçamentos
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

class SettingsController extends Controller
{
    /**
     * Exibe página de configurações com abas
     */
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('settings.view');

        $tab = $request->query('tab') ?? 'general';

        // Carrega configurações do arquivo
        $fileConfig = Config::all();

        // Carrega configurações do banco (editáveis)
        $db = Database::getInstance();
        $dbSettings = $db->fetchAll("SELECT `group`, `key`, value, type FROM settings ORDER BY `group`, `key`");

        $settings = [];
        foreach ($dbSettings as $row) {
            $settings[$row['group']][$row['key']] = $row;
        }

        return $this->adminView('settings/index', [
            'title' => 'Configurações',
            'tab' => $tab,
            'fileConfig' => $fileConfig,
            'settings' => $settings,
        ]);
    }

    /**
     * Salva configurações
     */
    public function update(Request $request, Response $response): void
    {
        $this->requirePermission('settings.manage');

        $tab = $request->input('tab') ?? 'general';
        $db = Database::getInstance();

        switch ($tab) {
            case 'general':
                $this->saveAppConfig($request);
                break;

            case 'database':
                $this->saveDatabaseConfig($request);
                break;

            case 'mail':
                $this->saveMailConfig($request);
                break;

            case 'branding':
                $this->saveBrandingSettings($request, $db);
                break;

            case 'seo':
                $this->saveSeoSettings($request, $db);
                break;

            case 'social':
                $this->saveSocialSettings($request, $db);
                break;

            case 'security':
                $this->saveSecurityConfig($request);
                break;

            case 'openai':
                $this->saveOpenAiConfig($request);
                break;

            case 'backup':
                $this->saveBackupConfig($request);
                break;

            case 'budget':
                $this->saveBudgetSettings($request, $db);
                break;

            case 'site':
                $this->saveSiteSettings($request, $db);
                break;
        }

        Logger::audit('Configurações atualizadas', ['tab' => $tab]);

        $this->session->flash('success', 'Configurações salvas com sucesso!');
        $this->redirect("/admin/configuracoes?tab={$tab}");
    }

    /**
     * Upload de logo
     */
    public function uploadLogo(Request $request, Response $response): void
    {
        $this->requirePermission('settings.manage');

        $type = $request->input('type') ?? 'logo_main';
        $file = $request->file('file');

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'Arquivo excede o limite do servidor (upload_max_filesize).',
                UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o limite do formulário.',
                UPLOAD_ERR_PARTIAL => 'Upload parcial, tente novamente.',
                UPLOAD_ERR_NO_FILE => 'Nenhum arquivo selecionado.',
                UPLOAD_ERR_NO_TMP_DIR => 'Diretório temporário não encontrado.',
                UPLOAD_ERR_CANT_WRITE => 'Falha ao gravar no disco.',
            ];
            $errorCode = $file['error'] ?? UPLOAD_ERR_NO_FILE;
            $msg = $errorMessages[$errorCode] ?? 'Erro desconhecido no upload (código ' . $errorCode . ').';

            Logger::error('Upload de logo falhou', ['type' => $type, 'error_code' => $errorCode]);
            $this->session->flash('error', $msg);
            $this->redirect('/admin/configuracoes?tab=branding');
            return;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->session->flash('error', 'Tipo de arquivo não permitido: ' . $file['type']);
            $this->redirect('/admin/configuracoes?tab=branding');
            return;
        }

        $uploadDir = ROOT_PATH . '/public/assets/img/branding/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $type . '_' . time() . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $path = '/assets/img/branding/' . $filename;
            Config::setSetting('branding', $type, $path);

            Logger::audit("Logo atualizado: {$type}", ['path' => $path]);
            $this->session->flash('success', 'Logo atualizado com sucesso!');
        } else {
            Logger::error('move_uploaded_file falhou', ['type' => $type, 'dest' => $destination]);
            $this->session->flash('error', 'Erro ao mover arquivo. Verifique permissões do diretório.');
        }

        $this->redirect('/admin/configuracoes?tab=branding');
    }

    // === Métodos privados de salvamento ===

    private function saveAppConfig(Request $request): void
    {
        Config::saveToFile([
            'app' => [
                'name' => $request->input('app_name') ?? 'LRV Web',
                'url' => rtrim($request->input('app_url') ?? '', '/'),
                'env' => $request->input('app_env') ?? 'production',
                'debug' => (bool) $request->input('app_debug'),
                'timezone' => $request->input('app_timezone') ?? 'America/Sao_Paulo',
                'locale' => $request->input('app_locale') ?? 'pt',
                'available_locales' => array_filter(explode(',', $request->input('app_locales') ?? 'pt,en,es')),
            ],
        ]);

        // Também salva no banco para uso em views
        $db = Database::getInstance();
        Config::setSetting('general', 'site_name', $request->input('app_name'));
        Config::setSetting('general', 'site_url', $request->input('app_url'));
    }

    private function saveDatabaseConfig(Request $request): void
    {
        Config::saveToFile([
            'database' => [
                'host' => $request->input('db_host') ?? 'localhost',
                'port' => (int) ($request->input('db_port') ?? 3306),
                'database' => $request->input('db_database') ?? 'lrvweb',
                'username' => $request->input('db_username') ?? 'root',
                'password' => $request->input('db_password') ?? '',
                'charset' => 'utf8mb4',
            ],
        ]);
    }

    private function saveMailConfig(Request $request): void
    {
        Config::saveToFile([
            'mail' => [
                'host' => $request->input('mail_host') ?? '',
                'port' => (int) ($request->input('mail_port') ?? 587),
                'username' => $request->input('mail_username') ?? '',
                'password' => $request->input('mail_password') ?? '',
                'encryption' => $request->input('mail_encryption') ?? 'tls',
                'from_address' => $request->input('mail_from_address') ?? '',
                'from_name' => $request->input('mail_from_name') ?? '',
            ],
        ]);
    }

    private function saveSecurityConfig(Request $request): void
    {
        Config::saveToFile([
            'security' => [
                'csrf_enabled' => (bool) $request->input('csrf_enabled'),
                'rate_limit_requests' => (int) ($request->input('rate_limit_requests') ?? 60),
                'rate_limit_window' => (int) ($request->input('rate_limit_window') ?? 60),
            ],
            'session' => [
                'lifetime' => (int) ($request->input('session_lifetime') ?? 120),
                'secure' => (bool) $request->input('session_secure'),
                'name' => 'lrvweb_session',
            ],
        ]);
    }

    private function saveOpenAiConfig(Request $request): void
    {
        Config::saveToFile([
            'openai' => [
                'api_key' => $request->input('openai_api_key') ?? '',
                'model' => $request->input('openai_model') ?? 'gpt-4',
                'blog_frequency' => $request->input('openai_frequency') ?? 'weekly',
                'blog_enabled' => (bool) $request->input('openai_enabled'),
                'blog_languages' => array_filter(explode(',', $request->input('openai_languages') ?? 'pt,en,es')),
            ],
        ]);
    }

    private function saveBackupConfig(Request $request): void
    {
        Config::saveToFile([
            'backup' => [
                'enabled' => (bool) $request->input('backup_enabled'),
                'frequency' => $request->input('backup_frequency') ?? 'daily',
                'retention_days' => (int) ($request->input('backup_retention') ?? 30),
                'path' => 'storage/backups',
            ],
        ]);
    }

    private function saveBrandingSettings(Request $request, Database $db): void
    {
        $fields = ['site_name', 'site_description'];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value !== null) {
                Config::setSetting('branding', $field, $value);
            }
        }
    }

    private function saveSeoSettings(Request $request, Database $db): void
    {
        $fields = ['meta_title', 'meta_description', 'meta_keywords', 'google_analytics', 'google_tag_manager'];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value !== null) {
                Config::setSetting('seo', $field, $value);
            }
        }
    }

    private function saveSocialSettings(Request $request, Database $db): void
    {
        $fields = ['instagram', 'facebook', 'linkedin', 'youtube', 'github', 'twitter', 'tiktok'];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value !== null) {
                Config::setSetting('social', $field, $value);
            }
        }
    }

    private function saveSiteSettings(Request $request, Database $db): void
    {
        $fields = ['site_email', 'site_phone', 'site_whatsapp', 'site_address', 'footer_text'];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value !== null) {
                Config::setSetting('general', $field, $value);
            }
        }
    }

    private function saveBudgetSettings(Request $request, Database $db): void
    {
        $fields = ['about_company', 'default_validity_days', 'terms'];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value !== null) {
                Config::setSetting('budget', $field, $value);
            }
        }
    }
}
