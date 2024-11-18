<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118122807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directors ADD CONSTRAINT FK_A6ADADC4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A6ADADC4A76ED395 ON directors (user_id)');
        $this->addSql('ALTER TABLE inst_qwith_link_title ADD inst_qwith_link_subtitle_id INT NOT NULL');
        $this->addSql('ALTER TABLE inst_qwith_link_title ADD CONSTRAINT FK_2DBED92FF4E1260E FOREIGN KEY (inst_qwith_link_subtitle_id) REFERENCES inst_qwith_link_subtitle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2DBED92FF4E1260E ON inst_qwith_link_title (inst_qwith_link_subtitle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE directors DROP CONSTRAINT FK_A6ADADC4A76ED395');
        $this->addSql('DROP INDEX IDX_A6ADADC4A76ED395');
        $this->addSql('ALTER TABLE inst_qwith_link_title DROP CONSTRAINT FK_2DBED92FF4E1260E');
        $this->addSql('DROP INDEX IDX_2DBED92FF4E1260E');
        $this->addSql('ALTER TABLE inst_qwith_link_title DROP inst_qwith_link_subtitle_id');
    }
}
