<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319151024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE social_activities_subtitle ALTER title_id SET NOT NULL');
        $this->addSql('ALTER TABLE user_social_activities ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_social_activities ADD social_activities_subtitle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_social_activities ADD CONSTRAINT FK_83BDA60CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_social_activities ADD CONSTRAINT FK_83BDA60C8922B495 FOREIGN KEY (social_activities_subtitle_id) REFERENCES social_activities_subtitle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_83BDA60CA76ED395 ON user_social_activities (user_id)');
        $this->addSql('CREATE INDEX IDX_83BDA60C8922B495 ON user_social_activities (social_activities_subtitle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_social_activities DROP CONSTRAINT FK_83BDA60CA76ED395');
        $this->addSql('ALTER TABLE user_social_activities DROP CONSTRAINT FK_83BDA60C8922B495');
        $this->addSql('DROP INDEX IDX_83BDA60CA76ED395');
        $this->addSql('DROP INDEX IDX_83BDA60C8922B495');
        $this->addSql('ALTER TABLE user_social_activities DROP user_id');
        $this->addSql('ALTER TABLE user_social_activities DROP social_activities_subtitle_id');
        $this->addSql('ALTER TABLE social_activities_subtitle ALTER title_id DROP NOT NULL');
    }
}
