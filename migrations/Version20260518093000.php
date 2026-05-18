<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260518093000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete all existing teacher and institute awards answers.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM teacher_answer');
        $this->addSql('DELETE FROM institute_answer');
    }

    public function down(Schema $schema): void
    {
        // Data deletion is irreversible.
    }
}

