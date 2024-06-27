<?php

namespace App\Dto\RatingDto;



use Symfony\Component\Validator\Constraints\IsNull;

class QuestionGetSubDto
{
    public function __construct(
        public int $id,
        #[isNull]
        public string $name,
    )
    {
    }

}