<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217165205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_inst_qout_link DROP CONSTRAINT fk_54cb6484697b0f4c');
        $this->addSql('DROP INDEX idx_54cb6484697b0f4c');
        $this->addSql('ALTER TABLE user_inst_qout_link DROP institute_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_inst_qout_link ADD institute_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_inst_qout_link ADD CONSTRAINT fk_54cb6484697b0f4c FOREIGN KEY (institute_id) REFERENCES institutions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_54cb6484697b0f4c ON user_inst_qout_link (institute_id)');
    }
}
