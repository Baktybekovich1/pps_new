<?php

namespace App\Dto\RatingDto;

class PpsRatingDto
{
    public function __construct(
        public int             $id,
        readonly public string $name,
        readonly public string $institute,
        public int             $researchPoints,
        public int             $awardPoints,
        public int             $innovativePoints,
        public int             $socialPoints,
        public int             $sum
    )
    {
    }
}