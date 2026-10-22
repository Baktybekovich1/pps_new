<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Organizations become fully independent: each builds its own questionnaire
 * and year calendar from scratch. Everything recorded before the split moves
 * to the archive — rows with no organization, which no organization sees.
 * Nothing is deleted.
 */
final class Version20261022000000 extends AbstractMigration
{
    private const QUESTION_TABLES = [
        'question_title' => 'FK_1BDD696F32C8A3DE',
        'question_subtitle' => 'FK_328D27BD32C8A3DE',
        'institute_question_subtitle' => 'FK_E2CE34E532C8A3DE',
    ];

    public function getDescription(): string
    {
        return 'Give questions an organization and move pre-split answers, results and adjustments to the archive.';
    }

    public function up(Schema $schema): void
    {
        foreach (self::QUESTION_TABLES as $table => $fk) {
            $this->addSql(sprintf('ALTER TABLE %s ADD organization_id INT DEFAULT NULL', $table));
            $this->addSql(sprintf(
                'ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE',
                $table,
                $fk
            ));
            $this->addSql(sprintf('CREATE INDEX %s ON %s (organization_id)', str_replace('FK_', 'IDX_', $fk), $table));
        }

        // Questions inherit the organization of what they hang off, so that
        // nothing created before this migration ends up owned by accident.
        $this->addSql('UPDATE question_title qt SET organization_id = s.organization_id FROM stage s WHERE qt.stage_id = s.id');
        $this->addSql('UPDATE question_subtitle qs SET organization_id = qt.organization_id FROM question_title qt WHERE qs.title_id = qt.id');
        $this->addSql('UPDATE institute_question_subtitle iqs SET organization_id = iqt.organization_id FROM institute_question_title iqt WHERE iqs.title_id = iqt.id');

        // Version20260813000000 attributed old answers, results and
        // adjustments to their teacher's organization. They were given against
        // the shared questionnaire and the shared calendar, which are archive
        // now, so they go with them.
        $this->addSql('
            UPDATE teacher_answer ta SET organization_id = NULL
            FROM question_subtitle qs
            WHERE ta.subtitle_id = qs.id AND qs.organization_id IS NULL
        ');
        $this->addSql('
            UPDATE teacher_answer ta SET organization_id = NULL
            FROM years y
            WHERE ta.academic_year_id = y.id AND y.organization_id IS NULL
        ');
        $this->addSql('
            UPDATE results_of_year r SET organization_id = NULL
            FROM years y
            WHERE r.year_id = y.id AND y.organization_id IS NULL
        ');
        $this->addSql('
            UPDATE expert_adjustment ea SET organization_id = NULL
            FROM years y
            WHERE ea.academic_year_id = y.id AND y.organization_id IS NULL
        ');
        $this->addSql('UPDATE expert_adjustment SET organization_id = NULL WHERE academic_year_id IS NULL');
    }

    public function down(Schema $schema): void
    {
        foreach (self::QUESTION_TABLES as $table => $fk) {
            $this->addSql(sprintf('ALTER TABLE %s DROP CONSTRAINT %s', $table, $fk));
            $this->addSql(sprintf('DROP INDEX %s', str_replace('FK_', 'IDX_', $fk)));
            $this->addSql(sprintf('ALTER TABLE %s DROP organization_id', $table));
        }

        // Restore the attribution Version20260813000000 made.
        $unambiguousTeacherOrg = '
            SELECT teacher_id, MIN(organization_id) AS organization_id
            FROM teacher_organization
            GROUP BY teacher_id
            HAVING COUNT(DISTINCT organization_id) = 1
        ';
        $this->addSql("
            UPDATE teacher_answer ta SET organization_id = sub.organization_id
            FROM ($unambiguousTeacherOrg) sub
            WHERE ta.teacher_id = sub.teacher_id AND ta.organization_id IS NULL
        ");
        $this->addSql("
            UPDATE results_of_year r SET organization_id = sub.organization_id
            FROM ($unambiguousTeacherOrg) sub
            WHERE r.teacher_id = sub.teacher_id AND r.organization_id IS NULL
        ");
        $this->addSql('
            UPDATE expert_adjustment ea SET organization_id = i.organization_id
            FROM institute i
            WHERE ea.target_institute_id = i.id AND ea.organization_id IS NULL
        ');
        $this->addSql("
            UPDATE expert_adjustment ea SET organization_id = sub.organization_id
            FROM ($unambiguousTeacherOrg) sub
            WHERE ea.target_teacher_id = sub.teacher_id AND ea.organization_id IS NULL
        ");
    }
}
