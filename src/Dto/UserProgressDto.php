<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints\NotBlank;

class UserProgressDto
{
    public function __construct(
        readonly public int $degree,
        readonly public int $rank,
        readonly public array $awards
    )
    {
    }

}