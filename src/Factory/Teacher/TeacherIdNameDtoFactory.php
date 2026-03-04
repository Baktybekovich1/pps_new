<?php

namespace App\Factory\Teacher;

use App\Dto\Teacher\TeacherIdNameDto;
use App\Entity\Teacher;

class TeacherIdNameDtoFactory
{
    public function __construct()
    {
    }

    public function factory(Teacher $teacher): TeacherIdNameDto
    {
        $dto = new TeacherIdNameDto();
        $dto->teacherId = $teacher->getId();
        $dto->name = (string)$teacher;
        return $dto;
    }

}