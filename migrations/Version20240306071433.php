<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306071433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE awards_and_link ADD name_id INT NOT NULL');
        $this->addSql('ALTER TABLE awards_and_link DROP name');
        $this->addSql('ALTER TABLE user_progress ADD degree_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_progress DROP degree');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE awards_and_link ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE awards_and_link DROP name_id');
        $this->addSql('ALTER TABLE user_progress ADD degree VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_progress DROP degree_id');
    }
}
