<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118125154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inst_qwith_link_title DROP CONSTRAINT fk_2dbed92ff4e1260e');
        $this->addSql('DROP INDEX idx_2dbed92ff4e1260e');
        $this->addSql('ALTER TABLE inst_qwith_link_title DROP inst_qwith_link_subtitle_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE inst_qwith_link_title ADD inst_qwith_link_subtitle_id INT NOT NULL');
        $this->addSql('ALTER TABLE inst_qwith_link_title ADD CONSTRAINT fk_2dbed92ff4e1260e FOREIGN KEY (inst_qwith_link_subtitle_id) REFERENCES inst_qwith_link_subtitle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2dbed92ff4e1260e ON inst_qwith_link_title (inst_qwith_link_subtitle_id)');
    }
}
