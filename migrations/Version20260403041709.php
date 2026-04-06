<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260403041709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE institute DROP CONSTRAINT FK_CA55B5D032C8A3DE');
        $this->addSql('ALTER TABLE institute ADD CONSTRAINT FK_CA55B5D032C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE institute_answer DROP CONSTRAINT FK_2B2962FC697B0F4C');
        $this->addSql('ALTER TABLE institute_answer ADD CONSTRAINT FK_2B2962FC697B0F4C FOREIGN KEY (institute_id) REFERENCES institute (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_organization DROP CONSTRAINT FK_5731974E41807E1D');
        $this->addSql('ALTER TABLE teacher_organization DROP CONSTRAINT FK_5731974E32C8A3DE');
        $this->addSql('ALTER TABLE teacher_organization DROP CONSTRAINT FK_5731974E697B0F4C');
        $this->addSql('ALTER TABLE teacher_organization ADD CONSTRAINT FK_5731974E41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_organization ADD CONSTRAINT FK_5731974E32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_organization ADD CONSTRAINT FK_5731974E697B0F4C FOREIGN KEY (institute_id) REFERENCES institute (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE institute DROP CONSTRAINT fk_ca55b5d032c8a3de');
        $this->addSql('ALTER TABLE institute ADD CONSTRAINT fk_ca55b5d032c8a3de FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE institute_answer DROP CONSTRAINT fk_2b2962fc697b0f4c');
        $this->addSql('ALTER TABLE institute_answer ADD CONSTRAINT fk_2b2962fc697b0f4c FOREIGN KEY (institute_id) REFERENCES institute (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_organization DROP CONSTRAINT fk_5731974e41807e1d');
        $this->addSql('ALTER TABLE teacher_organization DROP CONSTRAINT fk_5731974e32c8a3de');
        $this->addSql('ALTER TABLE teacher_organization DROP CONSTRAINT fk_5731974e697b0f4c');
        $this->addSql('ALTER TABLE teacher_organization ADD CONSTRAINT fk_5731974e41807e1d FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_organization ADD CONSTRAINT fk_5731974e32c8a3de FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_organization ADD CONSTRAINT fk_5731974e697b0f4c FOREIGN KEY (institute_id) REFERENCES institute (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
