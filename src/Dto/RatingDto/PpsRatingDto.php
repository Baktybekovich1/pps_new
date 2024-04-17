<?php

namespace App\Dto\RatingDto;

class PpsRatingDto
{
    public function __construct(
        public int $id,
        readonly public string $name,
        public int $researchPoints,
        public int $awardPoints,
        public int $innovativePoints,
        public int $socialPoints,
        public int $sum
    )
    {
    }
}