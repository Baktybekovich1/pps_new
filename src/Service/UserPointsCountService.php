<?php

namespace App\Service;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\PpsRatingSumDto;
use App\Entity\UserOffence;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\YearsRepository;

class UserPointsCountService
{


    public function __construct(
        private readonly TeacherAnswerRepository              $teacherAnswerRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly TeacherRepository                    $teacherRepository,
        private readonly ExpertAdjustmentRepository           $expertAdjustmentRepository,
        private readonly YearsRepository                     $yearsRepository
    ) {
    }

    public function UserPointsCount(): array
    {
        $pps = [];
        $teachers = $this->teacherRepository->findAll();

        foreach ($teachers as $teacher) {
            // Find organization/institute for teacher
            $firstOrg = $teacher->getTeacherOrganizations()->first();
            if (!$firstOrg) continue;

            $institute = $firstOrg->getInstitute();
            if ($institute->getUniversity() != 'МУИТ') {
                continue;
            }

            $fun = $this->getBigPoints($teacher);

            $pps[$teacher->getId()] = new PpsRatingDto(
                $teacher->getId(),
                (string)$teacher,
                $institute->getName(),
                $fun['research'],
                $fun['awards'],
                $fun['innovative'],
                $fun['social'],
                $fun['sum'],
                $fun['expert']
            );
        }
        return $pps;

    }

    public function getBigPoints($teacher)
    {
        // Calculate points from TeacherAnswer based on Stage names or IDs
        // In this project (based on previous edits and TeacherAnswerRepository):
        // Stage 1: Research, Stage 2: Awards, Stage 3: Innovative, Stage 4: Social (example mapping)
        
        $currentYear = $this->yearsRepository->findCurrentYear();
        
        $researchPoints = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 1, $currentYear);
        $awardPoints = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 2, $currentYear);
        $innovativePoints = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 3, $currentYear);
        $socialPoints = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 4, $currentYear);
        
        $sum = $researchPoints + $awardPoints + $innovativePoints + $socialPoints;

        // Deduct offences (offsets)
        // UserOffence still exists and might relate to User entity. 
        // We assume User and Teacher share IDs or have a relationship.
        $offence = $this->userOffenceRepository->getUserPoints($teacher->getId());
        $sum -= $offence;

        // Add expert adjustments
        $expertPointsSum = $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacher->getId(), $currentYear);
        $sum += $expertPointsSum;

        return [
            'research' => $researchPoints, 
            'awards' => $awardPoints, 
            'innovative' => $innovativePoints, 
            'social' => $socialPoints, 
            'sum' => $sum,
            'expert' => $expertPointsSum
        ];
    }

}
