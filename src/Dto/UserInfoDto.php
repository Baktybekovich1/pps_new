<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints\NotBlank;

class UserInfoDto
{
    public function __construct(
        #[NotBlank]
        readonly public string $name,
        #[NotBlank]
        readonly public string $institut,
        #[NotBlank]
        readonly public string $position,
        #[NotBlank]
        readonly public string $regular,
        readonly public string $email
    )
    {
    }

}