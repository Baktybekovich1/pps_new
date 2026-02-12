<?php

namespace App\Controller\Auth;

use App\Model\Step2Request;
use App\Repository\InstituteRepository;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class RegistrationStep2Action extends AbstractController
{
    public function __construct(
        private PositionRepository $positionRepo,
        private InstituteRepository $instituteRepo,
        private EntityManagerInterface $em
    ) {}

    #[Route('/api/me/registration-step2', name: 'me_registration_step2', methods: ['PUT'])]
    public function __invoke(
        #[CurrentUser] \App\Entity\Teacher $teacher,
        #[MapRequestPayload] Step2Request $dto
    ): Response {
        // 1. простые поля
        $teacher
            ->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setMiddleName($dto->middleName);

        // 2. должность
        $position = $this->positionRepo->find($dto->positionId);
        if (!$position) {
            return $this->json(['error' => 'Должность не найдена'], 422);
        }
        $teacher->setPosition($position);

        // 3. очищаем старые workplaces
        foreach ($teacher->getTeacherOrganizations() as $to) {
            $this->em->remove($to);
        }
        $this->em->flush();

        // 4. создаём новые
        foreach ($dto->workplaces as $wp) {
            $institute = $this->instituteRepo->find($wp->instituteId);
            if (!$institute || $institute->getOrganization()->getId() !== $wp->organizationId) {
                return $this->json(['error' => 'Институт не принадлежит организации'], 422);
            }
            $to = new \App\Entity\TeacherOrganization();
            $to->setTeacher($teacher)
                ->setOrganization($institute->getOrganization())
                ->setInstitute($institute)
                ->setRegular($wp->regular)
            ;
            $this->em->persist($to);
        }

        $this->em->flush();

        return $this->json($teacher, 200, [], ['groups' => 'teacher:read']);
    }
}