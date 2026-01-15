<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260113055122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE director DROP CONSTRAINT fk_1e90d3f0a76ed395');
        $this->addSql('ALTER TABLE director DROP CONSTRAINT fk_1e90d3f0e3badb69');
        $this->addSql('DROP INDEX idx_1e90d3f0e3badb69');
        $this->addSql('DROP INDEX idx_1e90d3f0a76ed395');
        $this->addSql('ALTER TABLE director ADD teacher_id INT NOT NULL');
        $this->addSql('ALTER TABLE director ADD institute_id INT NOT NULL');
        $this->addSql('ALTER TABLE director DROP user_id');
        $this->addSql('ALTER TABLE director DROP institutions_id');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT FK_1E90D3F041807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT FK_1E90D3F0697B0F4C FOREIGN KEY (institute_id) REFERENCES institute (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1E90D3F041807E1D ON director (teacher_id)');
        $this->addSql('CREATE INDEX IDX_1E90D3F0697B0F4C ON director (institute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE director DROP CONSTRAINT FK_1E90D3F041807E1D');
        $this->addSql('ALTER TABLE director DROP CONSTRAINT FK_1E90D3F0697B0F4C');
        $this->addSql('DROP INDEX IDX_1E90D3F041807E1D');
        $this->addSql('DROP INDEX IDX_1E90D3F0697B0F4C');
        $this->addSql('ALTER TABLE director ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE director ADD institutions_id INT NOT NULL');
        $this->addSql('ALTER TABLE director DROP teacher_id');
        $this->addSql('ALTER TABLE director DROP institute_id');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT fk_1e90d3f0a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director ADD CONSTRAINT fk_1e90d3f0e3badb69 FOREIGN KEY (institutions_id) REFERENCES institutions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1e90d3f0e3badb69 ON director (institutions_id)');
        $this->addSql('CREATE INDEX idx_1e90d3f0a76ed395 ON director (user_id)');
    }
}
