<?php

declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Dto\Answer\LinkDto;
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
        try {
            $result = $this->teacherAnswerService->save($user->getUserIdentifier(), $dto);
            return $this->json($result);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 423);
        }
    }

    #[Route(path: '/answers', name: 'answers', methods: ['GET'])]
    public function answers(UserInterface $user): JsonResponse
    {
        return $this->json($this->teacherAnswerService->getAll($user->getUserIdentifier()));
    }

    #[Route(path: '/answers/{answerId}', name: 'answers_delete', methods: ['DELETE'])]
    public function answers_delete(UserInterface $user, Request $request): JsonResponse
    {
        return $this->json($this->teacherAnswerService->delete($user->getUserIdentifier(), $request->get('answerId')));
    }

    #[Route(path: '/answers/{answerId}', name: 'answers_edit', methods: ['PUT'])]
    public function answers_edit(UserInterface $user, Request $request, #[MapRequestPayload] LinkDto $dto): JsonResponse
    {
        try {
            $result = $this->teacherAnswerService->edit($user->getUserIdentifier(), $request->get('answerId'), $dto->answerLink);
            return $this->json($result);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 423);
        }
    }
}
