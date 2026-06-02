<?php

declare(strict_types=1);

class Migration_20260602000002_add_pix_discount_to_budgets
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            ALTER TABLE budgets 
            ADD COLUMN pix_discount_enabled TINYINT(1) DEFAULT 0 AFTER payment_pix,
            ADD COLUMN pix_discount_percent DECIMAL(5,2) DEFAULT 5.00 AFTER pix_discount_enabled
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            ALTER TABLE budgets 
            DROP COLUMN pix_discount_enabled,
            DROP COLUMN pix_discount_percent
        ");
    }
}
