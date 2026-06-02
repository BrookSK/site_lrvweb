<?php

/**
 * CLI de Seeders
 * 
 * Executa os seeders para popular o banco com dados iniciais.
 * 
 * Uso:
 *   php cli/seed.php run    - Executa todos os seeders
 */

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

define('APP_ENV', 'development');
define('APP_DEBUG', true);

$action = $argv[1] ?? 'run';

if ($action !== 'run') {
    echo "Uso: php cli/seed.php run\n";
    exit(1);
}

try {
    \Core\Config::load(ROOT_PATH . '/config/app.php');

    $host = \Core\Config::get('database.host', 'localhost');
    $port = \Core\Config::get('database.port', 3306);
    $database = \Core\Config::get('database.database', 'lrvweb');
    $username = \Core\Config::get('database.username', 'root');
    $password = \Core\Config::get('database.password', '');

    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $seedersPath = ROOT_PATH . '/database/seeders/';
    $files = scandir($seedersPath);
    $seeders = array_filter($files, fn($f) => str_ends_with($f, '.php'));

    sort($seeders);

    foreach ($seeders as $file) {
        echo "▶ Executando seeder: {$file}...";

        require_once $seedersPath . $file;
        $className = 'Seeder_' . pathinfo($file, PATHINFO_FILENAME);
        $seeder = new $className();
        $seeder->run($pdo);

        echo " ✓\n";
    }

    echo "\n✓ Seeders executados com sucesso.\n";
} catch (Throwable $e) {
    echo "\n✗ Erro: " . $e->getMessage() . "\n";
    exit(1);
}
