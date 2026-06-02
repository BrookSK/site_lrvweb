<?php

/**
 * Model de Usuário
 * 
 * @package App\Models
 */

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    protected static array $fillable = [
        'name', 'email', 'password', 'role_id', 'avatar',
        'phone', 'position', 'is_active',
    ];

    protected static array $hidden = ['password', 'remember_token', 'password_reset_token'];

    /**
     * Busca usuário por e-mail
     */
    public static function findByEmail(string $email): ?static
    {
        return static::whereFirst('email', $email);
    }

    /**
     * Verifica senha
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Define senha com hash seguro
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_ARGON2ID);
    }
}
