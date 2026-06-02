<?php

/**
 * LRV Web Platform - Front Controller
 * 
 * Ponto de entrada único da aplicação.
 * Todas as requisições são direcionadas para cá via .htaccess
 * 
 * @package LRVWeb
 * @version 1.0.0
 */

declare(strict_types=1);

// Define o diretório raiz do projeto
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('START_TIME', microtime(true));

// Autoload do Composer
require ROOT_PATH . '/vendor/autoload.php';

// Inicializa a aplicação
$app = require ROOT_PATH . '/bootstrap/app.php';

// Executa a aplicação
$app->run();
