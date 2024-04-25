<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240312044250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE awards_and_link DROP CONSTRAINT fk_113e0979eac04e5c');
        $this->addSql('ALTER TABLE user_progress DROP CONSTRAINT fk_c28c1646a76ed395');
        $this->addSql('DROP TABLE state_awards');
        $this->addSql('DROP TABLE academic_degree');
        $this->addSql('DROP TABLE academic_rank');
        $this->addSql('DROP TABLE awards');
        $this->addSql('DROP TABLE comtehno_pps');
        $this->addSql('DROP TABLE awards_and_link');
        $this->addSql('DROP TABLE user_progress');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE state_awards (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) NOT NULL, points INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE academic_degree (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) NOT NULL, points INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE academic_rank (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) NOT NULL, points INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE awards (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, title VARCHAR(255) NOT NULL, total INT NOT NULL, date INT DEFAULT NULL, file TEXT DEFAULT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE comtehno_pps (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, total INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE awards_and_link (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, link TEXT NOT NULL, user_progress_id INT NOT NULL, name_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_113e0979eac04e5c ON awards_and_link (user_progress_id)');
        $this->addSql('CREATE TABLE user_progress (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, user_id INT DEFAULT NULL, degree_id INT NOT NULL, rank_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c28c1646a76ed395 ON user_progress (user_id)');
        $this->addSql('ALTER TABLE awards_and_link ADD CONSTRAINT fk_113e0979eac04e5c FOREIGN KEY (user_progress_id) REFERENCES user_progress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_progress ADD CONSTRAINT fk_c28c1646a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}