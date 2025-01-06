<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101183041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE director DROP CONSTRAINT fk_1e90d3f0697b0f4c');
        $this->addSql('DROP INDEX idx_1e90d3f0697b0f4c');
        $this->addSql('ALTER TABLE director RENAME COLUMN institute_id TO institutions_id');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT FK_1E90D3F0E3BADB69 FOREIGN KEY (institutions_id) REFERENCES institutions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1E90D3F0E3BADB69 ON director (institutions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE director DROP CONSTRAINT FK_1E90D3F0E3BADB69');
        $this->addSql('DROP INDEX IDX_1E90D3F0E3BADB69');
        $this->addSql('ALTER TABLE director RENAME COLUMN institutions_id TO institute_id');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT fk_1e90d3f0697b0f4c FOREIGN KEY (institute_id) REFERENCES institutions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1e90d3f0697b0f4c ON director (institute_id)');
    }
}
