<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240310133059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_research_activities_list ADD subtitle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_research_activities_list DROP ral_id');
        $this->addSql('ALTER TABLE user_research_activities_list ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE user_research_activities_list ADD CONSTRAINT FK_EFBE6D92A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_research_activities_list ADD CONSTRAINT FK_EFBE6D9210F3A34 FOREIGN KEY (subtitle_id) REFERENCES research_activities_subtitle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EFBE6D92A76ED395 ON user_research_activities_list (user_id)');
        $this->addSql('CREATE INDEX IDX_EFBE6D9210F3A34 ON user_research_activities_list (subtitle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_research_activities_list DROP CONSTRAINT FK_EFBE6D92A76ED395');
        $this->addSql('ALTER TABLE user_research_activities_list DROP CONSTRAINT FK_EFBE6D9210F3A34');
        $this->addSql('DROP INDEX IDX_EFBE6D92A76ED395');
        $this->addSql('DROP INDEX IDX_EFBE6D9210F3A34');
        $this->addSql('ALTER TABLE user_research_activities_list ADD ral_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_research_activities_list DROP subtitle_id');
        $this->addSql('ALTER TABLE user_research_activities_list ALTER user_id SET NOT NULL');
    }
}
