<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260113064428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE director DROP CONSTRAINT fk_1e90d3f041807e1d');
        $this->addSql('DROP INDEX idx_1e90d3f041807e1d');
        $this->addSql('ALTER TABLE director RENAME COLUMN teacher_id TO user_id');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT FK_1E90D3F0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1E90D3F0A76ED395 ON director (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE director DROP CONSTRAINT FK_1E90D3F0A76ED395');
        $this->addSql('DROP INDEX IDX_1E90D3F0A76ED395');
        $this->addSql('ALTER TABLE director RENAME COLUMN user_id TO teacher_id');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT fk_1e90d3f041807e1d FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1e90d3f041807e1d ON director (teacher_id)');
    }
}
