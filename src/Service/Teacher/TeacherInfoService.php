<?php

namespace App\Service\Teacher;

use App\Dto\Teacher\TeacherInfoDto;
use App\Entity\Teacher;
use App\Entity\TeacherOrganization;
use App\Factory\Teacher\TeacherInfoDtoFactory;
use App\Repository\InstituteRepository;
use App\Tenant\OrganizationFilterConfigurator;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Repository\PositionRepository;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeacherInfoService
{

    public function __construct(
        private TeacherRepository     $teacherRepository,
        private TeacherInfoDtoFactory $teacherInfoDtoFactory, private readonly TeacherOrganizationRepository $teacherOrganizationRepository, private readonly InstituteRepository $instituteRepository, private readonly PositionRepository $positionRepository, private readonly OrganizationFilterConfigurator $isolation,
    )
    {
    }

    public function service(string $email): TeacherInfoDto
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        return $this->teacherInfoDtoFactory->factory($teacher);
    }

    public function update(string $email, TeacherInfoDto $dto): bool
    {
        // The account is the caller's own; the id in the payload is ignored so
        // nobody can rewrite another teacher's workplaces.
        $teacher = $this->teacherRepository->findOneBy(['email' => $email])
            ?? throw new NotFoundHttpException('Teacher not found');

        $workplaces = [];
        foreach ($dto->institutes as $institute) {
            $workplace = $this->isolation->withoutIsolation(fn () => $this->instituteRepository->find($institute->instituteId));
            if (null === $workplace || $workplace->getOrganization()->getId() !== $institute->organizationId) {
                throw new UnprocessableEntityHttpException('Институт не принадлежит организации');
            }
            $workplaces[] = [$workplace, $institute->isRegular];
        }

        $teacher->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setMiddleName($dto->middleName)
            ->setPosition($this->positionRepository->find($dto->positionId));
        if (!$this->teacherRepository->save($teacher)) {
            return false;
        }

        $this->teacherOrganizationRepository->deleteByTeacher($teacher);
        foreach ($workplaces as [$workplace, $isRegular]) {
            $org = new TeacherOrganization();
            $org->setOrganization($workplace->getOrganization())
                ->setInstitute($workplace)
                ->setTeacher($teacher)
                ->setActive(true)
                ->setRegular($isRegular);
            $this->teacherOrganizationRepository->save($org);
        }

        return true;
    }

}