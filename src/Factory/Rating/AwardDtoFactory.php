<?php

namespace App\Factory\Rating;

use App\Dto\RatingDto\AwardDto;
use App\Entity\QuestionTitle;
use App\Entity\Stage;
use App\Entity\Teacher;
use App\Entity\Years;
use App\Repository\TeacherRepository;

class AwardDtoFactory
{
    public function __construct(
        private TeacherRepository $teacherRepository,
    )
    {
    }

    public function factory(Stage $stage, Teacher $teacher, ?Years $year = null): AwardDto
    {

        $awardDto = new AwardDto(
            $stage->getId(),
            $stage->getName(),
            $this->teacherRepository->findTeacherTitlePointsTotal($stage, $teacher, $year)
        );
        return $awardDto;

    }

}
