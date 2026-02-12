<?php

namespace App\Dto\Institute;

class InstituteInTeacherDto
{
    public function __construct(
        public int $organizationId,
        public string $organizationName,
        public int $instituteId,
        public string $instituteName,
        public bool $isRegular,
    )
    {
    }

}