<?php

/**
 * Configuração Principal da Aplicação
 * 
 * Este arquivo é o único ponto de configuração do sistema.
 * Todas as credenciais e parâmetros ficam aqui.
 * 
 * Em produção, este arquivo deve ter permissões restritas (chmod 600).
 * 
 * @package Config
 */

return [

    // === APLICAÇÃO ===
    'app' => [
        'name' => 'LRV Web',
        'url' => 'http://localhost',
        'env' => 'development', // development, staging, production
        'debug' => true,
        'version' => '1.0.0',
        'key' => 'ALTERE_PARA_STRING_ALEATORIA_DE_32_CARACTERES',
        'timezone' => 'America/Sao_Paulo',
        'locale' => 'pt',
        'fallback_locale' => 'pt',
        'available_locales' => ['pt', 'en', 'es'],
    ],

    // === BANCO DE DADOS ===
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'site_lrvweb',
        'username' => 'site_lrvweb',
        'password' => 'GobsPt2$aSdo24a%',
        'charset' => 'utf8mb4',
    ],

    // === E-MAIL (SMTP) ===
    'mail' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',
        'from_address' => 'contato@lrvweb.com.br',
        'from_name' => 'LRV Web',
    ],

    // === OPENAI (Blog com IA) ===
    'openai' => [
        'api_key' => '',
        'model' => 'gpt-4',
        'blog_frequency' => 'weekly', // daily, weekly
        'blog_enabled' => false,
        'blog_languages' => ['pt', 'en', 'es'],
    ],

    // === CACHE ===
    'cache' => [
        'driver' => 'file', // file
        'ttl' => 3600,
    ],

    // === SESSÃO ===
    'session' => [
        'lifetime' => 120, // minutos
        'secure' => false,
        'name' => 'lrvweb_session',
    ],

    // === UPLOAD ===
    'upload' => [
        'max_size' => 10485760, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip'],
        'path' => 'storage/uploads',
    ],

    // === SEGURANÇA ===
    'security' => [
        'csrf_enabled' => true,
        'rate_limit_requests' => 60,
        'rate_limit_window' => 60, // segundos
    ],

    // === LOGS ===
    'log' => [
        'channel' => 'daily', // daily, single
        'level' => 'debug',
    ],

    // === BACKUP ===
    'backup' => [
        'enabled' => true,
        'frequency' => 'daily',
        'retention_days' => 30,
        'path' => 'storage/backups',
    ],

];
