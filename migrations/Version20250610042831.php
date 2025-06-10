<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610042831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE results_of_year ADD research_points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE results_of_year ADD innovative_points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE results_of_year ADD social_points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE results_of_year ADD sum_points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE results_of_year RENAME COLUMN points TO award_points');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE results_of_year ADD points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE results_of_year DROP award_points');
        $this->addSql('ALTER TABLE results_of_year DROP research_points');
        $this->addSql('ALTER TABLE results_of_year DROP innovative_points');
        $this->addSql('ALTER TABLE results_of_year DROP social_points');
        $this->addSql('ALTER TABLE results_of_year DROP sum_points');
    }
}
