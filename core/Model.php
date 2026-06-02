<?php

/**
 * Model Base
 * 
 * Classe abstrata com funcionalidades de ORM simplificado.
 * Suporta CRUD, relacionamentos e query builder básico.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $hidden = ['password'];
    protected static bool $timestamps = true;

    protected array $attributes = [];
    protected array $original = [];
    protected bool $exists = false;

    /**
     * Retorna a conexão do banco de dados
     */
    protected static function db(): Database
    {
        return Database::getInstance();
    }

    /**
     * Busca por ID
     */
    public static function find(int|string $id): ?static
    {
        $result = static::db()->fetchOne(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id LIMIT 1",
            ['id' => $id]
        );

        if (!$result) {
            return null;
        }

        return static::hydrate($result);
    }

    /**
     * Busca por ID ou lança exceção
     */
    public static function findOrFail(int|string $id): static
    {
        $model = static::find($id);

        if (!$model) {
            throw new \RuntimeException(static::class . " com ID {$id} não encontrado.");
        }

        return $model;
    }

    /**
     * Retorna todos os registros
     */
    public static function all(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $results = static::db()->fetchAll(
            "SELECT * FROM " . static::$table . " ORDER BY {$orderBy} {$direction}"
        );

        return array_map(fn($row) => static::hydrate($row), $results);
    }

    /**
     * Busca com condições
     */
    public static function where(string $column, mixed $value, string $operator = '='): array
    {
        $results = static::db()->fetchAll(
            "SELECT * FROM " . static::$table . " WHERE {$column} {$operator} :value",
            ['value' => $value]
        );

        return array_map(fn($row) => static::hydrate($row), $results);
    }

    /**
     * Busca primeiro resultado com condição
     */
    public static function whereFirst(string $column, mixed $value, string $operator = '='): ?static
    {
        $result = static::db()->fetchOne(
            "SELECT * FROM " . static::$table . " WHERE {$column} {$operator} :value LIMIT 1",
            ['value' => $value]
        );

        return $result ? static::hydrate($result) : null;
    }

    /**
     * Conta registros
     */
    public static function count(?string $where = null, array $params = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM " . static::$table;

        if ($where) {
            $sql .= " WHERE {$where}";
        }

        $result = static::db()->fetchOne($sql, $params);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Cria um novo registro
     */
    public static function create(array $data): static
    {
        $data = static::filterFillable($data);

        if (static::$timestamps) {
            $now = date('Y-m-d H:i:s');
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
        }

        $id = static::db()->insert(static::$table, $data);
        $data[static::$primaryKey] = $id;

        $model = static::hydrate($data);
        $model->exists = true;

        return $model;
    }

    /**
     * Atualiza o registro
     */
    public function update(array $data): bool
    {
        $data = static::filterFillable($data);

        if (static::$timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $result = static::db()->update(
            static::$table,
            $data,
            static::$primaryKey . ' = :id',
            ['id' => $this->getId()]
        );

        if ($result) {
            $this->attributes = array_merge($this->attributes, $data);
        }

        return $result;
    }

    /**
     * Exclui o registro
     */
    public function delete(): bool
    {
        return static::db()->delete(
            static::$table,
            static::$primaryKey . ' = :id',
            ['id' => $this->getId()]
        );
    }

    /**
     * Soft delete
     */
    public function softDelete(): bool
    {
        return $this->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Paginação
     */
    public static function paginate(int $page = 1, int $perPage = 15, ?string $where = null, array $params = []): array
    {
        $offset = ($page - 1) * $perPage;
        $total = static::count($where, $params);

        $sql = "SELECT * FROM " . static::$table;
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $sql .= " ORDER BY " . static::$primaryKey . " DESC LIMIT {$perPage} OFFSET {$offset}";

        $results = static::db()->fetchAll($sql, $params);

        return [
            'data' => array_map(fn($row) => static::hydrate($row), $results),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int) ceil($total / $perPage),
        ];
    }

    /**
     * Retorna o ID do registro
     */
    public function getId(): int|string|null
    {
        return $this->attributes[static::$primaryKey] ?? null;
    }

    /**
     * Getter mágico
     */
    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Setter mágico
     */
    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Isset mágico
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Converte para array (excluindo campos ocultos)
     */
    public function toArray(): array
    {
        return array_diff_key($this->attributes, array_flip(static::$hidden));
    }

    /**
     * Converte para JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Filtra campos preenchíveis
     */
    protected static function filterFillable(array $data): array
    {
        if (empty(static::$fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip(static::$fillable));
    }

    /**
     * Hidrata um modelo a partir de um array
     */
    protected static function hydrate(array $data): static
    {
        $model = new static();
        $model->attributes = $data;
        $model->original = $data;
        $model->exists = true;

        return $model;
    }
}
