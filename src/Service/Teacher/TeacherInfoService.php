<?php

namespace App\Service\Teacher;

use App\Dto\Teacher\TeacherInfoDto;
use App\Entity\Teacher;
use App\Entity\TeacherOrganization;
use App\Factory\Teacher\TeacherInfoDtoFactory;
use App\Repository\InstituteRepository;
use App\Repository\OrganizationRepository;
use App\Repository\PositionRepository;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeacherInfoService
{

    public function __construct(
        private TeacherRepository     $teacherRepository,
        private TeacherInfoDtoFactory $teacherInfoDtoFactory, private readonly TeacherOrganizationRepository $teacherOrganizationRepository, private readonly OrganizationRepository $organizationRepository, private readonly InstituteRepository $instituteRepository, private readonly PositionRepository $positionRepository,
    )
    {
    }

    public function service(string $email): TeacherInfoDto
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        return $this->teacherInfoDtoFactory->factory($teacher);
    }

    public function update(TeacherInfoDto $dto): bool
    {
        $teacher = $this->teacherRepository->find($dto->id)
            ?? throw new NotFoundHttpException('Teacher not found');
        $teacher->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setMiddleName($dto->middleName)
            ->setPosition($this->positionRepository->find($dto->positionId));
        if ($this->teacherRepository->save($teacher)) {
            $this->teacherOrganizationRepository->deleteByTeacher($teacher);
            foreach ($dto->institutes as $institute) {
                $org = new TeacherOrganization();
                $org->setOrganization($this->organizationRepository->find($institute->organizationId))
                    ->setInstitute($this->instituteRepository->find($institute->instituteId))
                    ->setTeacher($teacher)
                    ->setActive(true)
                    ->setRegular($institute->isRegular);
                return $this->teacherOrganizationRepository->save($org);
            }
        }
        else return false;
        return false;
    }

}