<?php

namespace App\Service\Teacher;

use App\Factory\Teacher\TeacherIdNameDtoFactory;
use App\Repository\TeacherRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class TeacherService
{
    public function __construct(
        private TeacherRepository       $teacherRepository,
        private TeacherIdNameDtoFactory $teacherIdNameDtoFactory,
    )
    {
    }

    public function getAllTeachers(): array
    {
        $teachers = $this->teacherRepository->findAll();
        $result = [];
        foreach ($teachers as $teacher) {
            $result[] = $this->teacherIdNameDtoFactory->factory($teacher);
        }
        return $result;
    }

}