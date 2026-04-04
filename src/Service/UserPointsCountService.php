<?php

namespace App\Service;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\PpsRatingSumDto;
use App\Entity\UserOffence;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;

class UserPointsCountService
{


    public function __construct(
        private readonly TeacherAnswerRepository              $teacherAnswerRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly TeacherRepository                    $teacherRepository,
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly ExpertAdjustmentRepository           $expertAdjustmentRepository
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
                $fun['sum']
            );
        }
        return $pps;

    }

    public function getBigPoints($teacher)
    {
        // Calculate points from TeacherAnswer based on Stage names or IDs
        // In this project (based on previous edits and TeacherAnswerRepository):
        // Stage 1: Research, Stage 2: Awards, Stage 3: Innovative, Stage 4: Social (example mapping)
        
        $answers = $this->teacherAnswerRepository->findBy(['teacher' => $teacher, 'active' => true]);
        
        $researchPoints = 0;
        $awardPoints = 0;
        $innovativePoints = 0;
        $socialPoints = 0;
        $sum = 0;

        foreach ($answers as $answer) {
            $points = $answer->getSubtitle()->getPoint();
            $sum += $points;
            
            $stageId = $answer->getSubtitle()->getTitle()->getStage()->getId();
            
            // Map stage IDs to categories (this mapping might need refinement based on DB)
            switch ($stageId) {
                case 1: $researchPoints += $points; break;
                case 2: $awardPoints += $points; break;
                case 3: $innovativePoints += $points; break;
                case 4: $socialPoints += $points; break;
            }
        }

        // Deduct offences (offsets)
        // UserOffence still exists and might relate to User entity. 
        // We assume User and Teacher share IDs or have a relationship.
        $offence = $this->userOffenceRepository->getUserPoints($teacher->getId());
        $sum -= $offence;

        // Add expert adjustments
        $expertPointsSum = $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacher->getId());
        $sum += $expertPointsSum;

        return [
            'research' => $researchPoints, 
            'awards' => $awardPoints, 
            'innovative' => $innovativePoints, 
            'social' => $socialPoints, 
            'sum' => $sum
        ];
    }

}