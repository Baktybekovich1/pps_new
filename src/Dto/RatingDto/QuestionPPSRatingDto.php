<?php

namespace App\Dto\RatingDto;

class QuestionPPSRatingDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $institute,
        public int $point
    )
    {
    }

}