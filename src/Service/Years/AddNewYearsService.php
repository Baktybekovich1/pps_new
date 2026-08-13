<?php

namespace App\Service\Years;

use App\Entity\ResultsOfYear;
use App\Entity\Years;

use App\Repository\YearsRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\ResultsOfYearRepository;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\StageRepository;

readonly class AddNewYearsService
{
    // Which stage feeds which column of the snapshot. Previously these were
    // stage ids written inline, and they did not match the data: awards read
    // stage 2 (research) and research read stage 1 (awards), so every archived
    // year swapped the two. Social pointed at stage 4, which does not exist.
    private const STAGE_AWARDS = 'Личные достижения';
    private const STAGE_RESEARCH = 'Научно-исследовательская деятельность';
    private const STAGE_INNOVATIVE = 'Инновационно-образовательная деятельность';
    private const STAGE_SOCIAL = 'Социальная деятельность';

    public function __construct(
        private YearsRepository             $yearsRepository,
        private ResultsOfYearRepository     $resultsOfYearRepository,
        private TeacherRepository           $teacherRepository,
        private TeacherAnswerRepository     $teacherAnswerRepository,
        private ExpertAdjustmentRepository  $expertAdjustmentRepository,
        private StageRepository             $stageRepository
    )
    {
    }

    public function addYear(string $name): bool
    {
        // 1. Find and archive current year
        $currentYear = $this->yearsRepository->findCurrentYear();
        if ($currentYear) {
            $currentYear->setIsCurrent(false);
            $currentYear->setIsLocked(true);
            $this->yearsRepository->save($currentYear);
        }

        // 2. Create new active year
        $newYear = new Years();
        $newYear->setName($name);
        $newYear->setIsCurrent(true);
        $newYear->setIsLocked(false);
        $this->yearsRepository->save($newYear);

        // 3. Snapshot results for the archiving year (if exists)
        if ($currentYear) {
            $teachers = $this->teacherRepository->findAll();
            foreach ($teachers as $teacher) {
                $results = new ResultsOfYear();
                $results->setYear($currentYear);
                $results->setTeacher($teacher);
                
                $results->setAwardPoints($this->stagePoints($teacher->getId(), self::STAGE_AWARDS, $currentYear));
                $results->setResearchPoints($this->stagePoints($teacher->getId(), self::STAGE_RESEARCH, $currentYear));
                $results->setInnovativePoints($this->stagePoints($teacher->getId(), self::STAGE_INNOVATIVE, $currentYear));
                $results->setSocialPoints($this->stagePoints($teacher->getId(), self::STAGE_SOCIAL, $currentYear));
                // Scored against the year being archived. The service this
                // replaced asked for the current year, which step 2 above has
                // already moved on to, so the snapshot covered the wrong
                // period. The offence term is gone with user_offence.
                $results->setSumPoints(
                    $this->teacherAnswerRepository->getTeacherPointsCountByTeacherId($teacher->getId(), $currentYear)
                    + $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacher->getId(), $currentYear)
                );
                
                $this->resultsOfYearRepository->save($results);
            }
        }

        return true;
    }

    /**
     * Points a teacher scored in one stage, or 0 if this installation has no
     * such stage.
     *
     * Looked up by name rather than by id: the four columns on ResultsOfYear
     * are a fixed shape, while stages are rows an admin can add and reorder,
     * and cloning the questionnaire per organization will renumber them.
     */
    private function stagePoints(int $teacherId, string $stageName, Years $year): int
    {
        $stage = $this->stageRepository->findOneBy(['name' => $stageName]);
        if (null === $stage) {
            return 0;
        }

        return $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacherId, $stage->getId(), $year);
    }

}