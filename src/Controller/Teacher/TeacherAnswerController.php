<?php

declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Dto\Award\SetAwardDto;
use App\Service\Teacher\TeacherAnswerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TeacherAnswerController extends AbstractController
{
    public function __construct(private readonly TeacherAnswerService $teacherAnswerService)
    {
    }

    #[Route('/answer', methods: ['POST'])]
    public function index(UserInterface $user, #[MapRequestPayload] SetAwardDto $dto): JsonResponse
    {
        return $this->json( $this->teacherAnswerService->save($user->getUserIdentifier(), $dto));
    }

    #[Route(path: '/answers', name: 'answers', methods: ['GET'])]
    public function answers(UserInterface $user): JsonResponse
    {
        return $this->json($this->teacherAnswerService->getAll($user->getUserIdentifier()));
    }

}
