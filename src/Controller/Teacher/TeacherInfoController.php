<?php

declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Dto\Teacher\TeacherInfoDto;
use App\Dto\UserInfoGetDto;
use App\Repository\TeacherRepository;
use App\Service\Teacher\TeacherInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TeacherInfoController extends AbstractController
{
    public function __construct(
        private readonly TeacherRepository $teacherRepo,
        private readonly TeacherInfoService $teacherInfoService
    )
    {
    }

    #[Route('/name', name: 'app_user_name', methods: ['GET'])]
    public function user_name(UserInterface $user): JsonResponse
    {
        return $this->json([
            $this->teacherInfoService->service($user->getUserIdentifier()),
        ]);
    }
    #[Route('/id', name: 'app_user_id', methods: ['GET'])]
    public function id(UserInterface $userInterface): JsonResponse
    {
        $teacher = $this->teacherRepo->findOneBy(['email' => $userInterface->getUserIdentifier()]);
        return $this->json(["id" => $teacher->getId()]);
    }

    #[Route(path: '/profile/update', name: 'teacher_profile_update', methods: ['PATCH'])]
    public function teacher_profile_update(#[MapRequestPayload] TeacherInfoDto $dto): JsonResponse
    {
        return $this->json($this->teacherInfoService->update($dto));
    }
    
}
