<?php

namespace App\Dto\Years;

readonly class GetYearUserPointsDto
{
    public function __construct(
        public int    $userId,
        public string $userName,
        public int    $userPoints,
    )
    {
    }

}