<?php

namespace App\Dto\Institute;

class InstituteInTeacherDto
{
    public function __construct(
        public int $organizationId,
        public string $organizationName,
        public int $instituteId,
        public string $instituteName,
        // TeacherOrganization::$regular is nullable and starts out null, so a
        // membership nobody has classified yet must not blow up the profile.
        public ?bool $isRegular,
    )
    {
    }

}