<?php

namespace App\Factory\Teacher;

use App\Dto\Institute\InstituteInTeacherDto;
use App\Dto\Teacher\TeacherInfoDto;
use App\Entity\Teacher;
use App\Factory\Institute\InstituteInTeacherDtoFactory;
use App\Repository\TeacherOrganizationRepository;

readonly class TeacherInfoDtoFactory
{
    public function __construct(
        private TeacherOrganizationRepository $teacherOrganizationRepository,
        private InstituteInTeacherDtoFactory  $instituteInTeacherDtoFactory,
    )
    {
    }

    public function factory(Teacher $teacher): TeacherInfoDto
    {
        $teacherOrg = $this->teacherOrganizationRepository->findOneBy(['teacher' => $teacher]);
        $teacherInst = [];
        foreach ($teacherOrg as $item) {
            $teacherInst[] = $this->instituteInTeacherDtoFactory->factory($teacher, $item->getInstitute());
        }
        return new TeacherInfoDto(
            $teacher->getId(),
            $teacher->getFirstName(),
            $teacher->getLastName(),
            $teacher->getMiddleName(),
            $teacher->getEmail(),
            $teacherInst
        );

    }

}