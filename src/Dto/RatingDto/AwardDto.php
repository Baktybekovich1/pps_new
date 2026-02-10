<?php

namespace App\Dto\RatingDto;

class AwardDto
{

    public function __construct(
        public int $stageId,
        public string $stageTitle,
        public int $teacherPoint = 0
    )
    {
    }
}