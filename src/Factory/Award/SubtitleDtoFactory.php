<?php

namespace App\Factory\Award;

use App\Dto\Award\SubtitleDto;
use App\Entity\QuestionSubtitle;

class SubtitleDtoFactory
{
    public function __construct()
    {
    }

    public function factory(QuestionSubtitle $subtitle): SubtitleDto
    {
        $dto = new SubtitleDto();
        $dto->subtitleId = $subtitle->getId();
        $dto->subtitleName = $subtitle->getName();
        return $dto;
    }

}