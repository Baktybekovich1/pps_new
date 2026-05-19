<?php

namespace App\Factory\Rating;

use App\Dto\RatingDto\TeacherDto;
use App\Entity\Teacher;
use App\Entity\Years;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\StageRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\TeacherRepository;
use App\Repository\YearsRepository;

class TeacherDtoFactory
{

    public function __construct(
        private readonly StageRepository   $stageRepository,
        private readonly TeacherRepository $teacherRepository,
        private readonly AwardDtoFactory   $awardDtoFactory, 
        private readonly TeacherAnswerRepository $teacherAnswerRepository,
        private readonly YearsRepository $yearsRepository,
        private readonly ExpertAdjustmentRepository $expertAdjustmentRepository
    )
    {
    }

    public function factory(Teacher $teacher, ?Years $year = null): TeacherDto
    {
        $stages = $this->stageRepository->findAll();
        $awards = [];
        foreach ($stages as $stage) {
            $awards[] = $this->awardDtoFactory->factory($stage, $teacher, $year);
        }
        $selectedYear = $year ?? $this->yearsRepository->findCurrentYear();
        $baseTotal = $this->teacherAnswerRepository->getTeacherPointsCount($teacher, $selectedYear);
        $expertPoints = $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacher->getId(), $selectedYear);

        return new TeacherDto(
            $teacher->getId(),
            $teacher->getFirstName() . ' ' . $teacher->getLastName() . ' ' . $teacher->getMiddleName(),
            $awards,
            $baseTotal + $expertPoints,
            $expertPoints
        );
    }
}
