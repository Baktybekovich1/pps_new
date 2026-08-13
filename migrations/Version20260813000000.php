<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260813000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce organization ownership on tenant tables and backfill what is unambiguous.';
    }

    public function up(Schema $schema): void
    {
        foreach (self::tables() as $table => $fk) {
            $this->addSql(sprintf('ALTER TABLE %s ADD organization_id INT DEFAULT NULL', $table));
            $this->addSql(sprintf(
                'ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE',
                $table,
                $fk
            ));
            $this->addSql(sprintf('CREATE INDEX %s ON %s (organization_id)', str_replace('FK_', 'IDX_', $fk), $table));
        }

        // Rows whose organization is derivable are backfilled here. Teachers who
        // belong to more than one organization are skipped on purpose: their
        // answers cannot be split automatically and need an explicit decision.
        $unambiguousTeacherOrg = '
            SELECT teacher_id, MIN(organization_id) AS organization_id
            FROM teacher_organization
            GROUP BY teacher_id
            HAVING COUNT(DISTINCT organization_id) = 1
        ';

        $this->addSql("
            UPDATE teacher_answer ta
            SET organization_id = sub.organization_id
            FROM ($unambiguousTeacherOrg) sub
            WHERE ta.teacher_id = sub.teacher_id AND ta.organization_id IS NULL
        ");

        $this->addSql("
            UPDATE results_of_year roy
            SET organization_id = sub.organization_id
            FROM ($unambiguousTeacherOrg) sub
            WHERE roy.teacher_id = sub.teacher_id AND roy.organization_id IS NULL
        ");

        $this->addSql("
            UPDATE expert e
            SET organization_id = sub.organization_id
            FROM ($unambiguousTeacherOrg) sub
            WHERE e.teacher_id = sub.teacher_id AND e.organization_id IS NULL
        ");

        // An adjustment points either at an institute or at a teacher.
        $this->addSql('
            UPDATE expert_adjustment ea
            SET organization_id = i.organization_id
            FROM institute i
            WHERE ea.target_institute_id = i.id AND ea.organization_id IS NULL
        ');

        $this->addSql("
            UPDATE expert_adjustment ea
            SET organization_id = sub.organization_id
            FROM ($unambiguousTeacherOrg) sub
            WHERE ea.target_teacher_id = sub.teacher_id AND ea.organization_id IS NULL
        ");

        // stage, years, institute_question_title and "user" stay NULL: the
        // questionnaire and the year calendar are still shared by all three
        // organizations, and admins have to be assigned by hand. Splitting them
        // is the next migration.
    }

    public function down(Schema $schema): void
    {
        foreach (self::tables() as $table => $fk) {
            $this->addSql(sprintf('ALTER TABLE %s DROP CONSTRAINT %s', $table, $fk));
            $this->addSql(sprintf('DROP INDEX %s', str_replace('FK_', 'IDX_', $fk)));
            $this->addSql(sprintf('ALTER TABLE %s DROP organization_id', $table));
        }
    }

    /**
     * @return array<string, string> table name => foreign key constraint name
     */
    private static function tables(): array
    {
        return [
            'stage' => 'FK_C27C936932C8A3DE',
            'years' => 'FK_A308E87732C8A3DE',
            'teacher_answer' => 'FK_CF5698D032C8A3DE',
            'results_of_year' => 'FK_A932742D32C8A3DE',
            'expert' => 'FK_4F1B934232C8A3DE',
            'expert_adjustment' => 'FK_9022174732C8A3DE',
            'institute_question_title' => 'FK_58A514132C8A3DE',
            '"user"' => 'FK_8D93D64932C8A3DE',
        ];
    }
}
