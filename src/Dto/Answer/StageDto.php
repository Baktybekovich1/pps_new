<?php

namespace App\Dto\Answer;
use Symfony\Component\Validator\Constraints as Assert;

class StageDto
{
    public int $stageId;
    public string $stageName;
    /**
     * @var AnswerDto[]
     */
    #[Assert\Valid]
    public array $answers = [];
}