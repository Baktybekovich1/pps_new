<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240326161939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info ADD institutions_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_info DROP institut');
        $this->addSql('ALTER TABLE user_info ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9EE3BADB69 FOREIGN KEY (institutions_id) REFERENCES institutions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B1087D9EA76ED395 ON user_info (user_id)');
        $this->addSql('CREATE INDEX IDX_B1087D9EE3BADB69 ON user_info (institutions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_info DROP CONSTRAINT FK_B1087D9EA76ED395');
        $this->addSql('ALTER TABLE user_info DROP CONSTRAINT FK_B1087D9EE3BADB69');
        $this->addSql('DROP INDEX IDX_B1087D9EA76ED395');
        $this->addSql('DROP INDEX IDX_B1087D9EE3BADB69');
        $this->addSql('ALTER TABLE user_info ADD institut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_info DROP institutions_id');
        $this->addSql('ALTER TABLE user_info ALTER user_id SET NOT NULL');
    }
}
