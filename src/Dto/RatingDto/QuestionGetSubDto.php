<?php

namespace App\Dto\RatingDto;



use Symfony\Component\Validator\Constraints\IsNull;

class QuestionGetSubDto
{
    public function __construct(
        public int $id,
        public ?string $name,
    )
    {
    }

}