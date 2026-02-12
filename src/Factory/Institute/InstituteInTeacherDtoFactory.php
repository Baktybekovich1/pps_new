<?php

namespace App\Factory\Institute;

use App\Dto\Institute\InstituteInTeacherDto;
use App\Entity\Institute;
use App\Entity\Teacher;
use App\Repository\TeacherOrganizationRepository;

class InstituteInTeacherDtoFactory
{
    public function __construct(
        private TeacherOrganizationRepository $teacherOrganizationRepository
    )
    {
    }

    public function factory(Teacher $teacher, Institute $institute): InstituteInTeacherDto
    {
        $teacherOrganization = $this->teacherOrganizationRepository->findOneBy(['teacher' => $teacher, 'institute' => $institute]);
        return new InstituteInTeacherDto(
            $teacherOrganization->getOrganization()->getId(),
            $teacherOrganization->getOrganization()->getName(),
            $teacherOrganization->getInstitute()->getId(),
            $teacherOrganization->getInstitute()->getName(),
            $teacherOrganization->getRegular()
        );
    }
}