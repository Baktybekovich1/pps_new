<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306043750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_research_activities_and_link DROP CONSTRAINT fk_dd79931b2974865c');
        $this->addSql('DROP TABLE user_research_activities_and_link');
        $this->addSql('DROP TABLE user_research_activities');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE user_research_activities_and_link (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) DEFAULT NULL, link TEXT NOT NULL, ural_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_dd79931b2974865c ON user_research_activities_and_link (ural_id)');
        $this->addSql('CREATE TABLE user_research_activities (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) NOT NULL, user_id INT NOT NULL, points INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE user_research_activities_and_link ADD CONSTRAINT fk_dd79931b2974865c FOREIGN KEY (ural_id) REFERENCES user_research_activities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
