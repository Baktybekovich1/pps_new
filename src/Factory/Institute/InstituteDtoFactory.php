<?php

namespace App\Factory\Institute;

use App\Dto\Institute\InstituteDto;
use App\Entity\Institute;

class InstituteDtoFactory
{
    public function fromEntity(Institute $institute): InstituteDto
    {
        return new InstituteDto(
            $institute->getId(),
            $institute->getName(),
            $institute->getTeacherTotal(),
        );
    }
}