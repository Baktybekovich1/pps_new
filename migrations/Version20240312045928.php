<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240312045928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_personal_awards ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_personal_awards ADD subtitle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_personal_awards ADD CONSTRAINT FK_C3BA6366A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_personal_awards ADD CONSTRAINT FK_C3BA636610F3A34 FOREIGN KEY (subtitle_id) REFERENCES personal_awards_subtitle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C3BA6366A76ED395 ON user_personal_awards (user_id)');
        $this->addSql('CREATE INDEX IDX_C3BA636610F3A34 ON user_personal_awards (subtitle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_personal_awards DROP CONSTRAINT FK_C3BA6366A76ED395');
        $this->addSql('ALTER TABLE user_personal_awards DROP CONSTRAINT FK_C3BA636610F3A34');
        $this->addSql('DROP INDEX IDX_C3BA6366A76ED395');
        $this->addSql('DROP INDEX IDX_C3BA636610F3A34');
        $this->addSql('ALTER TABLE user_personal_awards DROP user_id');
        $this->addSql('ALTER TABLE user_personal_awards DROP subtitle_id');
    }
}
