<?php

namespace App\Factory\Director;

use App\Dto\Director\DirectoraNameDto;
use App\Entity\Director;

class DirectorNameDtoFactory
{
    public function __construct()
    {
    }

    public function factory(Director $director): DirectoraNameDto
    {
        $dto = new DirectoraNameDto();
        $dto->name = (string)$director->getTeacher();
        $dto->directorId = $director->getId();
        $dto->instituteId = $director->getInstitute()->getId();
        $dto->instituteName = $director->getInstitute()->getName();
        return $dto;
    }

}