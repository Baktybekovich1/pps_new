<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217164436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inst_qwith_link_subtitle DROP CONSTRAINT FK_726934113D5282CF');
        $this->addSql('ALTER TABLE inst_qwith_link_subtitle ADD CONSTRAINT FK_726934113D5282CF FOREIGN KEY (award_id) REFERENCES inst_qwith_link_title (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_inst_qwith_link ALTER award_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_inst_qwith_link ALTER award_id SET NOT NULL');
        $this->addSql('ALTER TABLE inst_qwith_link_subtitle DROP CONSTRAINT fk_726934113d5282cf');
        $this->addSql('ALTER TABLE inst_qwith_link_subtitle ADD CONSTRAINT fk_726934113d5282cf FOREIGN KEY (award_id) REFERENCES inst_qout_link_title (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
