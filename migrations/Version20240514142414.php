<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514142414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info ADD position_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_info DROP "position"');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9EDD842E46 FOREIGN KEY (position_id) REFERENCES position (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B1087D9EDD842E46 ON user_info (position_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_info DROP CONSTRAINT FK_B1087D9EDD842E46');
        $this->addSql('DROP INDEX IDX_B1087D9EDD842E46');
        $this->addSql('ALTER TABLE user_info ADD "position" VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_info DROP position_id');
    }
}
