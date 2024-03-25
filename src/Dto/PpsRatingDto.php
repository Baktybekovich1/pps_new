<?php

namespace App\Dto;

class PpsRatingDto
{
    public function __construct(
        public int $id,
        readonly public string $user,
        public int $uralPoints,
        public int $progressPoints,
        public int $educationPoints,
        public int $socialPoints
    )
    {
    }
}