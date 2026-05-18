<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260518100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Bind expert adjustments to academic year and backfill existing rows.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expert_adjustment ADD academic_year_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expert_adjustment ADD CONSTRAINT FK_90221747C54F3401 FOREIGN KEY (academic_year_id) REFERENCES years (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90221747C54F3401 ON expert_adjustment (academic_year_id)');

        // Backfill historical rows to current year.
        $this->addSql("
            UPDATE expert_adjustment ea
            SET academic_year_id = y.id
            FROM years y
            WHERE ea.academic_year_id IS NULL
              AND y.is_current = true
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expert_adjustment DROP CONSTRAINT FK_90221747C54F3401');
        $this->addSql('DROP INDEX IDX_90221747C54F3401');
        $this->addSql('ALTER TABLE expert_adjustment DROP academic_year_id');
    }
}

