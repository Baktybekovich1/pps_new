<?php

namespace App\Controller;

use App\Dto\OffencesDto\UserOffenceAddDto;
use App\Dto\OffencesDto\UserOffenceGetDto;
use App\Dto\OffencesDto\UserOffencesDto;
use App\Entity\UserOffence;
use App\Repository\TeacherRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class OffenceController extends AbstractController
{
    public function __construct(
        private UserOffenceRepository $userOffenceRepository,
        private UserRepository        $userRepository,
        private TeacherRepository     $teacherRepository
    )
    {
    }

    #[Route('/api/admin/offence', name: 'app_offence',methods: ['GET'])]
    public function index(): JsonResponse
    {
        $teachers = $this->teacherRepository->findAll();
        $dto = [];
        foreach ($teachers as $teacher) {
            $user = $this->userRepository->findOneBy(['username' => $teacher->getEmail()]);
            if (!$user) continue;

            $dto[] = new UserOffenceGetDto(
                $user->getId(),
                (string)$teacher,
                [] // No offences available since OffenceList is missing
            );
        }
        return $this->json([
            'offence' => $dto
        ]);
    }

    #[Route('/api/admin/offence/add', name: 'app_offence_add', methods: ['POST'])]
    public function add(#[MapRequestPayload] UserOffenceAddDto $dto): JsonResponse
    {
        $offences = $dto->offence;
        foreach ($offences as $offence) {
            $newOffence = new UserOffence();
            $newOffence->setUser($this->userRepository->find($offence['userId']));
            $newOffence->setQuantity($offence['quantity']);
            $this->userOffenceRepository->save($newOffence);
        }

        return $this->json([
            'Success'
        ]);
    }

}
