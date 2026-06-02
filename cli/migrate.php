<?php

/**
 * CLI de Migrations
 * 
 * Gerencia migrações de banco de dados.
 * 
 * Uso:
 *   php cli/migrate.php run        - Executa migrations pendentes
 *   php cli/migrate.php rollback   - Reverte última migration
 *   php cli/migrate.php status     - Exibe status das migrations
 *   php cli/migrate.php create     - Cria nova migration
 */

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

use Core\Config;

Config::load(ROOT_PATH . '/config/app.php');

define('APP_ENV', Config::get('app.env', 'development'));
define('APP_DEBUG', true);

$action = $argv[1] ?? 'status';
$name = $argv[2] ?? null;

$migrator = new MigrationRunner();

match ($action) {
    'run' => $migrator->run(),
    'rollback' => $migrator->rollback(),
    'status' => $migrator->status(),
    'create' => $migrator->create($name),
    default => echo "Uso: php cli/migrate.php [run|rollback|status|create] [nome]\n",
};

class MigrationRunner
{
    private \PDO $pdo;
    private string $migrationsPath;

    public function __construct()
    {
        $this->migrationsPath = ROOT_PATH . '/database/migrations/';
        $this->connect();
        $this->ensureMigrationsTable();
    }

    private function connect(): void
    {
        $host = Config::get('database.host', 'localhost');
        $port = Config::get('database.port', 3306);
        $database = Config::get('database.database', 'lrvweb');
        $username = Config::get('database.username', 'root');
        $password = Config::get('database.password', '');

        $this->pdo = new \PDO(
            "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
            $username,
            $password,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    private function ensureMigrationsTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function run(): void
    {
        $executed = $this->getExecuted();
        $files = $this->getMigrationFiles();
        $batch = $this->getNextBatch();
        $pending = array_diff($files, $executed);

        if (empty($pending)) {
            echo "✓ Nenhuma migration pendente.\n";
            return;
        }

        foreach ($pending as $file) {
            echo "▶ Executando: {$file}...";

            require_once $this->migrationsPath . $file;
            $className = $this->getClassName($file);
            $migration = new $className();
            $migration->up($this->pdo);

            $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)")
                ->execute([$file, $batch]);

            echo " ✓\n";
        }

        echo "\n✓ " . count($pending) . " migration(s) executada(s).\n";
    }

    public function rollback(): void
    {
        $batch = $this->getCurrentBatch();

        if ($batch === 0) {
            echo "✓ Nada para reverter.\n";
            return;
        }

        $stmt = $this->pdo->prepare("SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC");
        $stmt->execute([$batch]);
        $migrations = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($migrations as $file) {
            echo "◀ Revertendo: {$file}...";

            require_once $this->migrationsPath . $file;
            $className = $this->getClassName($file);
            $migration = new $className();
            $migration->down($this->pdo);

            $this->pdo->prepare("DELETE FROM migrations WHERE migration = ?")
                ->execute([$file]);

            echo " ✓\n";
        }

        echo "\n✓ " . count($migrations) . " migration(s) revertida(s).\n";
    }

    public function status(): void
    {
        $executed = $this->getExecuted();
        $files = $this->getMigrationFiles();

        echo "\n=== Status das Migrations ===\n\n";

        foreach ($files as $file) {
            $status = in_array($file, $executed) ? '✓' : '○';
            echo "  {$status} {$file}\n";
        }

        $pending = count(array_diff($files, $executed));
        echo "\nTotal: " . count($files) . " | Executadas: " . count($executed) . " | Pendentes: {$pending}\n";
    }

    public function create(?string $name): void
    {
        if (!$name) {
            echo "Erro: Informe o nome da migration.\n";
            echo "Uso: php cli/migrate.php create nome_da_migration\n";
            return;
        }

        if (!is_dir($this->migrationsPath)) {
            mkdir($this->migrationsPath, 0755, true);
        }

        $timestamp = date('YmdHis');
        $filename = "{$timestamp}_{$name}.php";
        $className = 'Migration_' . $timestamp . '_' . $name;

        $content = <<<PHP
<?php

declare(strict_types=1);

class {$className}
{
    public function up(PDO \$pdo): void
    {
        \$pdo->exec("
            -- SQL aqui
        ");
    }

    public function down(PDO \$pdo): void
    {
        \$pdo->exec("
            -- Reverter aqui
        ");
    }
}
PHP;

        file_put_contents($this->migrationsPath . $filename, $content);
        echo "✓ Migration criada: {$filename}\n";
    }

    private function getMigrationFiles(): array
    {
        if (!is_dir($this->migrationsPath)) {
            return [];
        }

        $files = scandir($this->migrationsPath);
        return array_values(array_filter($files, fn($f) => str_ends_with($f, '.php')));
    }

    private function getExecuted(): array
    {
        $stmt = $this->pdo->query("SELECT migration FROM migrations ORDER BY id");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getNextBatch(): int
    {
        return $this->getCurrentBatch() + 1;
    }

    private function getCurrentBatch(): int
    {
        $stmt = $this->pdo->query("SELECT MAX(batch) FROM migrations");
        return (int) $stmt->fetchColumn();
    }

    private function getClassName(string $file): string
    {
        $name = pathinfo($file, PATHINFO_FILENAME);
        return 'Migration_' . $name;
    }
}
