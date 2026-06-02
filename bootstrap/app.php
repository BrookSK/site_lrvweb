<?php

/**
 * Bootstrap da Aplicação
 * 
 * Inicializa todos os componentes do framework.
 * Carrega configurações do arquivo config/app.php
 */

declare(strict_types=1);

use Core\Application;
use Core\Config;

// Carrega configurações do arquivo
Config::load(ROOT_PATH . '/config/app.php');

// Define constantes de ambiente
define('APP_ENV', Config::get('app.env', 'production'));
define('APP_DEBUG', Config::get('app.debug', false));

// Configuração de erros baseada no ambiente
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Timezone
date_default_timezone_set(Config::get('app.timezone', 'America/Sao_Paulo'));

// Charset
mb_internal_encoding('UTF-8');

// Cria e retorna a instância da aplicação
$app = new Application();

return $app;
