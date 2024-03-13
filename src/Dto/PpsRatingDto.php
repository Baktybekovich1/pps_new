<?php

namespace App\Dto;

class PpsRatingDto
{
    public function __construct(
        readonly public string $user,
        public int $uralPoints,
        public int $progressPoints
    )
    {
    }
}