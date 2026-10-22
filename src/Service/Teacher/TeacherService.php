<?php

namespace App\Service\Teacher;

use App\Factory\Teacher\TeacherIdNameDtoFactory;
use App\Repository\TeacherRepository;
use App\Tenant\OrganizationContext;
use Symfony\Component\HttpFoundation\JsonResponse;

class TeacherService
{
    public function __construct(
        private TeacherRepository       $teacherRepository,
        private TeacherIdNameDtoFactory $teacherIdNameDtoFactory,
        private OrganizationContext     $organizationContext,
    )
    {
    }

    public function getAllTeachers(): array
    {
        $organization = $this->organizationContext->get();
        if (null === $organization && !$this->organizationContext->isCrossOrganization()) {
            return [];
        }

        $teachers = $this->teacherRepository->findWorkingFor($organization);
        $result = [];
        foreach ($teachers as $teacher) {
            $result[] = $this->teacherIdNameDtoFactory->factory($teacher);
        }
        return $result;
    }

}