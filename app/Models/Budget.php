<?php

/**
 * Model de Orçamento
 * 
 * @package App\Models
 */

declare(strict_types=1);

namespace App\Models;

use Core\Config;
use Core\Model;

class Budget extends Model
{
    protected static string $table = 'budgets';

    protected static array $fillable = [
        'client_id', 'hash', 'name', 'status', 'total_value', 'monthly_value',
        'discount_percent', 'discount_value', 'final_value', 'payment_type',
        'installments', 'installment_value', 'minimum_entry', 'validity_date',
        'payment_pix', 'payment_card', 'payment_boleto', 'notes',
        'internal_notes', 'about_company', 'created_by',
    ];

    /**
     * Gera URL pública do orçamento
     */
    public function getPublicUrl(): string
    {
        $baseUrl = Config::get('app.url', '');
        return rtrim($baseUrl, '/') . '/orcamento/' . $this->hash;
    }

    /**
     * Retorna blocos do orçamento
     */
    public function blocks(): array
    {
        return static::db()->fetchAll(
            "SELECT * FROM budget_blocks WHERE budget_id = :id ORDER BY sort_order",
            ['id' => $this->getId()]
        );
    }

    /**
     * Verifica se está expirado
     */
    public function isExpired(): bool
    {
        if (!$this->validity_date) {
            return false;
        }
        return strtotime($this->validity_date) < time();
    }
}
