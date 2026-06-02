<?php

/**
 * Model de Cliente
 * 
 * @package App\Models
 */

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Client extends Model
{
    protected static string $table = 'clients';

    protected static array $fillable = [
        'user_id', 'name', 'company', 'email', 'phone', 'whatsapp',
        'cpf_cnpj', 'address_street', 'address_number', 'address_complement',
        'address_neighborhood', 'address_city', 'address_state', 'address_zip',
        'website', 'social_instagram', 'social_facebook', 'social_linkedin',
        'notes', 'is_active',
    ];

    /**
     * Retorna projetos do cliente
     */
    public function projects(): array
    {
        return Project::where('client_id', $this->getId());
    }

    /**
     * Retorna orçamentos do cliente
     */
    public function budgets(): array
    {
        return Budget::where('client_id', $this->getId());
    }
}
