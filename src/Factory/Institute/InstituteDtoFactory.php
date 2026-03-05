<?php

namespace App\Factory\Institute;

use App\Dto\Institute\InstituteDto;
use App\Dto\Institute\InstituteNameDto;
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

    public function getName(Institute $institute): InstituteNameDto
    {
        $dto = new InstituteNameDto();
        $dto->instituteId = $institute->getId();
        $dto->name = (string)$institute;
        return $dto;

    }

}