<?php

/**
 * Model de Projeto
 * 
 * @package App\Models
 */

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Project extends Model
{
    protected static string $table = 'projects';

    protected static array $fillable = [
        'client_id', 'name', 'description', 'status',
        'value', 'start_date', 'due_date',
    ];

    /**
     * Retorna tarefas do projeto
     */
    public function tasks(): array
    {
        return static::db()->fetchAll(
            "SELECT * FROM project_tasks WHERE project_id = :id ORDER BY priority DESC, created_at",
            ['id' => $this->getId()]
        );
    }

    /**
     * Retorna membros do projeto
     */
    public function members(): array
    {
        return static::db()->fetchAll(
            "SELECT u.id, u.name, u.avatar, pm.role 
             FROM project_members pm 
             JOIN users u ON pm.user_id = u.id 
             WHERE pm.project_id = :id",
            ['id' => $this->getId()]
        );
    }
}
