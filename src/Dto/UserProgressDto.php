<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints\NotBlank;

class UserProgressDto
{
    public function __construct(
        readonly public string $degree,
        readonly public string $rank,
        readonly public array $awards
    )
    {
    }

}