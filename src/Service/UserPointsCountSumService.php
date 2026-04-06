<?php

namespace App\Service;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\PpsRatingSumDto;
use App\Repository\TeacherAnswerRepository;
use App\Repository\TeacherRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\YearsRepository;

class UserPointsCountSumService
{
    public function __construct(
        private readonly TeacherRepository            $teacherRepository,
        private readonly TeacherAnswerRepository      $teacherAnswerRepository,
        private readonly UserOffenceRepository        $userOffenceRepository,
        private readonly UserRepository               $userRepository,
        private readonly UserInfoRepository           $userInfoRepository,
        private readonly ExpertAdjustmentRepository   $expertAdjustmentRepository,
        private readonly YearsRepository             $yearsRepository
    )
    {
    }

    public function UserPointsCount(): array
    {
        $pps = [];
        $teachers = $this->teacherRepository->findAll();

        foreach ($teachers as $teacher) {
            $firstOrg = $teacher->getTeacherOrganizations()->first();
            if (!$firstOrg || $firstOrg->getInstitute()->getUniversity() !== 'МУИТ') {
                continue;
            }

            $fun = $this->getBigPoints($teacher);

            $pps[$teacher->getId()] = new PpsRatingSumDto(
                $teacher,
                $fun['sum'],
            );
        }
        return $pps;
    }

    public function getBigPoints($teacher)
    {
        $currentYear = $this->yearsRepository->findCurrentYear();
        $sum = $this->teacherAnswerRepository->getTeacherPointsCount($teacher, $currentYear);

        $sum -= $this->userOffenceRepository->getUserPoints($teacher->getId());
        $sum += $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacher->getId());

        return ['sum' => $sum];
    }

    public function getPoints($objects)
    {
        $coll = 0;
        foreach ($objects as $object) {
            $coll += $object->getPoints();
        }
        return $coll;

    }

}