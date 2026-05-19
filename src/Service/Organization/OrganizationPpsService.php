<?php

namespace App\Service\Organization;

use App\Entity\Years;
use App\Factory\Rating\TeacherDtoFactory;
use App\Repository\OrganizationRepository;
use App\Repository\QuestionTitleRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrganizationPpsService
{
    public function __construct(
        private readonly OrganizationRepository        $organizationRepository,
        private readonly TeacherRepository             $teacherRepository,
        private readonly TeacherAnswerRepository       $teacherAnswerRepository,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
        private readonly QuestionTitleRepository $questionTitleRepository,
        private readonly TeacherDtoFactory           $teacherDtoFactory,
    )
    {
    }

    public function organizationPps(int $organizationId, ?Years $year = null): array
    {

        $organization = $this->organizationRepository->find($organizationId);
        if (null === $organization) {
            throw new \RuntimeException('Organization not found');
        }
        $teacherOrganization = $this->teacherOrganizationRepository->findBy(['organization' => $organization]);
        $teachers = [];
        foreach ($teacherOrganization as $item) {
            $teachers[$item->getTeacher()->getId()] = $this->teacherDtoFactory->factory($item->getTeacher(), $year);
        }
        return $teachers;

    }
}
