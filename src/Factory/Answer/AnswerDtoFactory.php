<?php

namespace App\Factory\Answer;

use App\Dto\Answer\AnswerDto;
use App\Entity\TeacherAnswer;

class AnswerDtoFactory
{
    public function factory(TeacherAnswer $answer): AnswerDto
    {
        $dto = new AnswerDto();
        $dto->answerId = $answer->getId();
        $dto->answerName = $answer->getSubtitle()->getTitle()->getName() . ' ' . $answer->getSubtitle()->getName();
        $dto->answerLink = $answer->getLink();
        $dto->isActive = $answer->isActive();
        return $dto;
    }

}