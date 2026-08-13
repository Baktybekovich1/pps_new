<?php

namespace App\Service\Years;

use App\Entity\ResultsOfYear;
use App\Entity\Years;

use App\Repository\YearsRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\ResultsOfYearRepository;
use App\Repository\ExpertAdjustmentRepository;

readonly class AddNewYearsService
{
    public function __construct(
        private YearsRepository             $yearsRepository,
        private ResultsOfYearRepository     $resultsOfYearRepository,
        private TeacherRepository           $teacherRepository,
        private TeacherAnswerRepository     $teacherAnswerRepository,
        private ExpertAdjustmentRepository  $expertAdjustmentRepository
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
                
                $results->setAwardPoints($this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 2, $currentYear));
                $results->setResearchPoints($this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 1, $currentYear));
                $results->setInnovativePoints($this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 3, $currentYear));
                $results->setSocialPoints($this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 4, $currentYear));
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

}