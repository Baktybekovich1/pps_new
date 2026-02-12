<?php

namespace App\Dto\Teacher;

class TeacherInfoDto
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $middleName,
        public string $email,
        public array $institutes
    )
    {
    }

}