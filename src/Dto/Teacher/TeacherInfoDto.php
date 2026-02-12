<?php

namespace App\Dto\Teacher;

use App\Dto\Institute\InstituteInTeacherDto;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

class TeacherInfoDto
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $middleName,
        public string $email,
        public string $position,
        /**
         * @var InstituteInTeacherDto[]
         */
        #[Type(InstituteInTeacherDto::class)]
        #[Assert\Valid]
        public array $institutes = []
    )
    {
    }

}