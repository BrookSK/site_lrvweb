<?php

/**
 * Classe de Banco de Dados
 * 
 * Gerencia a conexão PDO e operações no banco.
 * Implementa Singleton para manter uma única conexão.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $host = Config::get('database.host', 'localhost');
        $port = Config::get('database.port', 3306);
        $database = Config::get('database.database', 'lrvweb');
        $username = Config::get('database.username', 'root');
        $password = Config::get('database.password', '');
        $charset = Config::get('database.charset', 'utf8mb4');

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";

        try {
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset}",
            ]);
        } catch (PDOException $e) {
            Logger::critical('Falha na conexão com o banco de dados', [
                'message' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Erro ao conectar ao banco de dados.');
        }
    }

    /**
     * Retorna instância singleton
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna a conexão PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Executa uma query preparada
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            Logger::error('Erro na query SQL', [
                'sql' => $sql,
                'params' => $params,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Retorna um único registro
     */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Retorna todos os registros
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Insere um registro
     */
    public function insert(string $table, array $data): int|string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($k) => ":{$k}", array_keys($data)));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);

        return $this->pdo->lastInsertId();
    }

    /**
     * Atualiza registros
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): bool
    {
        $set = implode(', ', array_map(fn($k) => "{$k} = :set_{$k}", array_keys($data)));

        // Prefixar params do SET para evitar conflito
        $setParams = [];
        foreach ($data as $key => $value) {
            $setParams["set_{$key}"] = $value;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        $this->query($sql, array_merge($setParams, $whereParams));

        return true;
    }

    /**
     * Exclui registros
     */
    public function delete(string $table, string $where, array $params = []): bool
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $this->query($sql, $params);
        return true;
    }

    /**
     * Inicia uma transação
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Confirma uma transação
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * Reverte uma transação
     */
    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    /**
     * Executa operação dentro de transação
     */
    public function transaction(callable $callback): mixed
    {
        $this->beginTransaction();

        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Impede clonagem
     */
    private function __clone()
    {
    }
}
