<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignUpDto
{
    public function __construct(
        #[NotBlank]
        readonly public string $username,
        #[Length(min: 6)]
        readonly public string $password
    )
    {
    }
}