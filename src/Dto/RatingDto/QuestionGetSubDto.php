<?php

namespace App\Dto\RatingDto;

class QuestionGetSubDto
{
    public function __construct(
        public int $id,
        public string $name,
    )
    {
    }

}