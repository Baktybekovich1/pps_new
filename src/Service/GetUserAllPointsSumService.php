<?php

namespace App\Service;

use App\Repository\TeacherAnswerRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\YearsRepository;

class GetUserAllPointsSumService
{
    public function __construct(
        private readonly TeacherAnswerRepository      $teacherAnswerRepository,
        private readonly UserOffenceRepository        $userOffenceRepository,
        private readonly ExpertAdjustmentRepository   $expertAdjustmentRepository,
        private readonly YearsRepository             $yearsRepository
    )
    {
    }

    public function getUserAllPointsSum(int $teacherId): int
    {
        $currentYear = $this->yearsRepository->findCurrentYear();
        $sum = $this->teacherAnswerRepository->getTeacherPointsCountByTeacherId($teacherId, $currentYear);
        $offence = $this->userOffenceRepository->getUserPoints($teacherId);
        $adjustment = $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacherId);

        return $sum - $offence + $adjustment;
    }

}