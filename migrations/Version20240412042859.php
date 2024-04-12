<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412042859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_innovative_education ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_personal_awards ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_research_activities_list ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_social_activities ADD status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_personal_awards DROP status');
        $this->addSql('ALTER TABLE user_social_activities DROP status');
        $this->addSql('ALTER TABLE user_research_activities_list DROP status');
        $this->addSql('ALTER TABLE user_innovative_education DROP status');
    }
}
