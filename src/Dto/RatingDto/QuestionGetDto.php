<?php

namespace App\Dto\RatingDto;

class QuestionGetDto
{
    public function __construct(
        public int $id,
        public string $name,
        public array $subtitle,
    )
    {
    }
}