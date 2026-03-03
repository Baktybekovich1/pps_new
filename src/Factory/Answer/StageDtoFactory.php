<?php

namespace App\Factory\Answer;

use App\Dto\Answer\StageDto;
use App\Entity\Stage;
use App\Entity\Teacher;
use App\Repository\TeacherAnswerRepository;

class StageDtoFactory
{
    public function __construct(
        private readonly TeacherAnswerRepository $teacherAnswerRepository,
        private readonly AnswerDtoFactory        $answerDtoFactory,
    )
    {
    }

    public function factory(Teacher $teacher, Stage $stage): StageDto
    {
        $stageDto = new StageDto();
        $stageDto->stageId = $stage->getId();
        $stageDto->stageName = $stage->getName();
        $answers = $this->teacherAnswerRepository->findTeacherAnswersByStage($teacher->getId(), $stage->getId());
        $awards = [];
        foreach ($answers as $answer) {
            $awards[] = $this->answerDtoFactory->factory($answer);
        }
        $stageDto->answers = $awards;
        return $stageDto;
    }

}