<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints\NotBlank;

class UserProgressDto
{
    public function __construct(
        readonly public array $awards
    )
    {
    }

}