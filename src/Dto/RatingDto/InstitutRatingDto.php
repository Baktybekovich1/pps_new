<?php

namespace App\Dto\RatingDto;


class InstitutRatingDto
{
    public function __construct(
        public int $id,
        public string $name,
        public int $middlePoints,
        public int $sum
    )
    {
    }

}