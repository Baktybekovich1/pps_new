<?php

namespace App\Factory\Answer;

use App\Dto\Answer\StageDto;
use App\Entity\Stage;
use App\Entity\Teacher;
use App\Entity\Years;
use App\Repository\TeacherAnswerRepository;

class StageDtoFactory
{
    public function __construct(
        private readonly TeacherAnswerRepository $teacherAnswerRepository,
        private readonly AnswerDtoFactory        $answerDtoFactory,
    )
    {
    }

    public function factory(Teacher $teacher, Stage $stage, ?Years $currentYear = null): StageDto
    {
        $stageDto = new StageDto();
        $stageDto->stageId = $stage->getId();
        $stageDto->stageName = $stage->getName();
        $answers = $this->teacherAnswerRepository->findTeacherAnswersByStageAndYear(
            $teacher->getId(),
            $stage->getId(),
            $currentYear
        );
        $awards = [];
        foreach ($answers as $answer) {
            $awards[] = $this->answerDtoFactory->factory($answer);
        }
        $stageDto->answers = $awards;
        return $stageDto;
    }

}
