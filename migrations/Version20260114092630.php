<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114092630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE director ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE teacher DROP has_trousers');
        $this->addSql('ALTER TABLE teacher_organization ADD regular BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE teacher_organization ADD active BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE director ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE teacher_organization DROP regular');
        $this->addSql('ALTER TABLE teacher_organization DROP active');
        $this->addSql('ALTER TABLE teacher ADD has_trousers BOOLEAN DEFAULT NULL');
    }
}
