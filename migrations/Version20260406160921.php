<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260406160921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cleanup of UserInfo, Institutions, OffenceList and mapping fixes.';
    }

    public function up(Schema $schema): void
    {
        // 1. Drop constraints FIRST to break dependencies
        $this->addSql('ALTER TABLE user_innovative_education DROP CONSTRAINT IF EXISTS fk_4043467aa76ed395');
        $this->addSql('ALTER TABLE user_innovative_education DROP CONSTRAINT IF EXISTS fk_4043467ae4776e89');
        $this->addSql('ALTER TABLE user_social_activities DROP CONSTRAINT IF EXISTS fk_83bda60ca76ed395');
        $this->addSql('ALTER TABLE user_social_activities DROP CONSTRAINT IF EXISTS fk_83bda60c8922b495');
        $this->addSql('ALTER TABLE user_personal_awards DROP CONSTRAINT IF EXISTS fk_c3ba6366a76ed395');
        $this->addSql('ALTER TABLE user_personal_awards DROP CONSTRAINT IF EXISTS fk_c3ba636610f3a34');
        $this->addSql('ALTER TABLE social_activities_subtitle DROP CONSTRAINT IF EXISTS fk_cfba2604a9f87bd');
        $this->addSql('ALTER TABLE research_activities_subtitle DROP CONSTRAINT IF EXISTS fk_9c84e5e712469de2');
        $this->addSql('ALTER TABLE user_research_activities_list DROP CONSTRAINT IF EXISTS fk_efbe6d92a76ed395');
        $this->addSql('ALTER TABLE user_research_activities_list DROP CONSTRAINT IF EXISTS fk_efbe6d9210f3a34');
        $this->addSql('ALTER TABLE user_info DROP CONSTRAINT IF EXISTS fk_b1087d9ea76ed395');
        $this->addSql('ALTER TABLE user_info DROP CONSTRAINT IF EXISTS fk_b1087d9ee3badb69');
        $this->addSql('ALTER TABLE user_info DROP CONSTRAINT IF EXISTS fk_b1087d9edd842e46');
        $this->addSql('ALTER TABLE personal_awards_subtitle DROP CONSTRAINT IF EXISTS fk_1f44198a9f87bd');
        $this->addSql('ALTER TABLE user_offence DROP CONSTRAINT IF EXISTS fk_4df9df7aa9dfb1a4');
        $this->addSql('ALTER TABLE results_of_year DROP CONSTRAINT IF EXISTS fk_a932742d9b6b5fba');

        // 2. Drop indexes
        $this->addSql('DROP INDEX IF EXISTS idx_4df9df7aa9dfb1a4');
        $this->addSql('DROP INDEX IF EXISTS idx_a932742d9b6b5fba');

        // 3. Drop tables now that dependencies are gone
        $this->addSql('DROP TABLE IF EXISTS offence_list');
        $this->addSql('DROP TABLE IF EXISTS user_innovative_education');
        $this->addSql('DROP TABLE IF EXISTS user_social_activities');
        $this->addSql('DROP TABLE IF EXISTS user_personal_awards');
        $this->addSql('DROP TABLE IF EXISTS social_activities_subtitle');
        $this->addSql('DROP TABLE IF EXISTS social_activities_list');
        $this->addSql('DROP TABLE IF EXISTS institutions');
        $this->addSql('DROP TABLE IF EXISTS research_activities_list');
        $this->addSql('DROP TABLE IF EXISTS research_activities_subtitle');
        $this->addSql('DROP TABLE IF EXISTS user_research_activities_list');
        $this->addSql('DROP TABLE IF EXISTS user_info');
        $this->addSql('DROP TABLE IF EXISTS personal_awards');
        $this->addSql('DROP TABLE IF EXISTS personal_awards_subtitle');

        // 4. Update existing tables
        $this->addSql('ALTER TABLE institute_answer ADD academic_year_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE institute_answer ADD CONSTRAINT FK_2B2962FCC54F3401 FOREIGN KEY (academic_year_id) REFERENCES years (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2B2962FCC54F3401 ON institute_answer (academic_year_id)');

        $this->addSql('ALTER TABLE results_of_year RENAME COLUMN account_id TO teacher_id');
        $this->addSql('ALTER TABLE results_of_year ADD CONSTRAINT FK_A932742D41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A932742D41807E1D ON results_of_year (teacher_id)');

        $this->addSql('ALTER TABLE stage ADD is_permanent BOOLEAN DEFAULT false');
        $this->addSql('ALTER TABLE teacher_answer ADD academic_year_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE teacher_answer ADD CONSTRAINT FK_CF5698D0C54F3401 FOREIGN KEY (academic_year_id) REFERENCES years (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CF5698D0C54F3401 ON teacher_answer (academic_year_id)');

        $this->addSql('ALTER TABLE user_offence DROP COLUMN IF EXISTS offence_list_id');

        $this->addSql('ALTER TABLE years ADD is_current BOOLEAN DEFAULT false');
        $this->addSql('ALTER TABLE years ADD is_locked BOOLEAN DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // down migration is not priority here as we are fixing a broken state
        $this->addSql('CREATE SCHEMA public');
    }
}
