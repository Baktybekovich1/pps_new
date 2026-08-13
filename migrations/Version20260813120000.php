<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260813120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop the User accounts and their offences: Teacher is now the only principal.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS user_offence');
        $this->addSql('DROP TABLE IF EXISTS "user"');
    }

    public function down(Schema $schema): void
    {
        // Recreates the shape only. The accounts and the recorded offences are
        // gone for good — restore them from a dump if they are needed.
        $this->addSql('CREATE TABLE "user" (
            id SERIAL NOT NULL,
            organization_id INT DEFAULT NULL,
            username VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE INDEX IDX_8D93D64932C8A3DE ON "user" (organization_id)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64932C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE TABLE user_offence (
            id SERIAL NOT NULL,
            user_id INT DEFAULT NULL,
            quantity INT DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_5A2D3B0EA76ED395 ON user_offence (user_id)');
        $this->addSql('ALTER TABLE user_offence ADD CONSTRAINT FK_5A2D3B0EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
