<?php

namespace App\Dto;

class UserInfoGetDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $institut,
        public string $position,
        public string $regular,
        public string $email
    )
    {
    }

}