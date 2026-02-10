<?php

namespace App\Factory\Rating;

use App\Dto\RatingDto\TeacherDto;
use App\Entity\Teacher;
use App\Repository\StageRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\TeacherRepository;

class TeacherDtoFactory
{

    public function __construct(
        private readonly StageRepository   $stageRepository,
        private readonly TeacherRepository $teacherRepository,
        private readonly AwardDtoFactory   $awardDtoFactory, private readonly TeacherAnswerRepository $teacherAnswerRepository,
    )
    {
    }

    public function factory(Teacher $teacher): TeacherDto
    {
        $stages = $this->stageRepository->findAll();
        $awards = [];
        foreach ($stages as $stage) {
            $awards[] = $this->awardDtoFactory->factory($stage, $teacher);
        }
        return new TeacherDto(
            $teacher->getId(),
            $teacher->getFirstName() . ' ' . $teacher->getLastName() . ' ' . $teacher->getMiddleName(),
            $awards,
            $this->teacherAnswerRepository->getTeacherPointsCount($teacher),
        );
    }
}