<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260518090000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Backfill academic year for teacher/institute answers and enforce current-year consistency.';
    }

    public function up(Schema $schema): void
    {
        // 1) Ensure exactly one current year exists (fallback: latest by id).
        $this->addSql("
            WITH chosen AS (
                SELECT id
                FROM years
                WHERE is_current = true
                ORDER BY id DESC
                LIMIT 1
            ),
            fallback AS (
                SELECT id
                FROM years
                ORDER BY id DESC
                LIMIT 1
            ),
            target AS (
                SELECT id FROM chosen
                UNION ALL
                SELECT id FROM fallback WHERE NOT EXISTS (SELECT 1 FROM chosen)
                LIMIT 1
            )
            UPDATE years
            SET is_current = CASE WHEN id IN (SELECT id FROM target) THEN true ELSE false END
            WHERE EXISTS (SELECT 1 FROM target)
        ");

        // 2) Backfill NULL academic years to current year.
        $this->addSql("
            UPDATE teacher_answer ta
            SET academic_year_id = y.id
            FROM years y
            WHERE ta.academic_year_id IS NULL
              AND y.is_current = true
        ");

        $this->addSql("
            UPDATE institute_answer ia
            SET academic_year_id = y.id
            FROM years y
            WHERE ia.academic_year_id IS NULL
              AND y.is_current = true
        ");
    }

    public function down(Schema $schema): void
    {
        // Non-destructive rollback: keep data as-is.
    }
}

