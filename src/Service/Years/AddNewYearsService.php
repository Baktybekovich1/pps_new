<?php

namespace App\Service\Years;

use App\Entity\ResultsOfYear;
use App\Entity\Years;

use App\Repository\YearsRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\ResultsOfYearRepository;
use App\Service\GetUserAllPointsSumService;

readonly class AddNewYearsService
{
    public function __construct(
        private YearsRepository            $yearsRepository,
        private ResultsOfYearRepository    $resultsOfYearRepository,
        private TeacherRepository          $teacherRepository,
        private TeacherAnswerRepository    $teacherAnswerRepository,
        private GetUserAllPointsSumService $getUserAllPointsSumService
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
                $results->setSumPoints($this->getUserAllPointsSumService->getUserAllPointsSum($teacher->getId()));
                
                $this->resultsOfYearRepository->save($results);
            }
        }

        return true;
    }

}