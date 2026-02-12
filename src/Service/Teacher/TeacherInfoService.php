<?php

namespace App\Service\Teacher;

use App\Dto\Teacher\TeacherInfoDto;
use App\Entity\Teacher;
use App\Factory\Teacher\TeacherInfoDtoFactory;
use App\Repository\TeacherRepository;

class TeacherInfoService
{

    public function __construct(
        private TeacherRepository $teacherRepository,
        private TeacherInfoDtoFactory $teacherInfoDtoFactory,
    )
    {
    }

    public function service(string $email): TeacherInfoDto
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        return $this->teacherInfoDtoFactory->factory($teacher);
    }

}