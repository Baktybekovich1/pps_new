<?php

namespace App\Controller\Admin;

use App\Repository\TeacherAnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Модерация наград преподавателей: замороженная награда не приносит баллов,
 * пока преподаватель не исправит ссылку или админ её не разморозит.
 */
#[Route('/teacher/answers')]
#[OA\Tag(name: 'Admin Teacher Answers')]
class AdminTeacherAnswersController extends AbstractController
{
    public function __construct(
        private readonly TeacherAnswerRepository $answerRepository,
        private readonly EntityManagerInterface  $entityManager,
    ) {
    }

    #[Route('/{id}', name: 'admin_teacher_answer_set_active', methods: ['PATCH'])]
    public function setActive(int $id, Request $request): JsonResponse
    {
        // Filtered to the admin's organization: another one's id is not found.
        $answer = $this->answerRepository->find($id);
        if (null === $answer) {
            return $this->json(['error' => 'Награда не найдена'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        if (!array_key_exists('active', $data)) {
            return $this->json(['error' => 'active is required'], 400);
        }

        $answer->setActive((bool) $data['active']);
        $this->entityManager->flush();

        return $this->json(['id' => $answer->getId(), 'active' => $answer->isActive()]);
    }
}
