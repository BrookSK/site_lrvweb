<?php

declare(strict_types=1);

class Migration_20260720000001_add_ai_transcript_to_budgets
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            ALTER TABLE budgets 
            ADD COLUMN ai_transcript TEXT NULL AFTER internal_notes
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            ALTER TABLE budgets 
            DROP COLUMN ai_transcript
        ");
    }
}
