<?php

namespace App\Dto\RatingDto;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionGetSubDto
{
    public function __construct(
        public int $id,
        #[Assert\IsNull]
        public string $name,
    )
    {
    }

}