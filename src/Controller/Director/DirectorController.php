<?php

namespace App\Controller\Director;

use App\Entity\Teacher;
use App\Service\Director\DirectorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DirectorController extends AbstractController
{
    public function __construct(private readonly DirectorService $directorService)
    {
    }

    /** Public: used by AdminDirectors page to list all directors */
    #[Route(path: '/', name: 'find_all', methods: ['GET'])]
    public function get_all(): JsonResponse
    {
        return $this->json($this->directorService->findAllDirectors());
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute', name: 'director_institute', methods: ['GET'])]
    public function get_institute(): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        return $this->json($this->directorService->getInstituteData($user));
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute', name: 'director_institute_update', methods: ['PUT'])]
    public function update_institute(Request $request): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $this->directorService->updateInstitute($user, (int)$request->get('teacherTotal'));
        return $this->json(['message' => 'Success']);
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute/answers', name: 'director_institute_answers', methods: ['GET'])]
    public function get_answers(): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        return $this->json($this->directorService->getInstituteAnswers($user));
    }

    #[Route(path: '/institute/answers/{id}', name: 'director_institute_answer_update', methods: ['PUT'])]
    public function update_answer(int $id, Request $request): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $this->directorService->updateInstituteAnswer($user, $id, (string)$request->get('answerLink'));
        return $this->json(['message' => 'Success']);
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute/answers', name: 'director_institute_answer_add', methods: ['POST'])]
    public function add_answer(Request $request): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $subtitleId = (int)($data['subtitleId'] ?? 0);
        $link = trim((string)($data['answerLink'] ?? ''));

        if (!$subtitleId || !$link) {
            return $this->json(['error' => 'subtitleId и answerLink обязательны'], 400);
        }

        if (!str_starts_with($link, 'http')) {
            $link = 'https://' . $link;
        }

        try {
            $result = $this->directorService->addInstituteAward($user, $subtitleId, $link);
            return $this->json($result, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute/awards', name: 'director_institute_awards_list', methods: ['GET'])]
    public function get_awards(): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        try {
            return $this->json($this->directorService->getInstituteAwards($user));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute/teacher/{id}', name: 'director_institute_teacher_remove', methods: ['DELETE'])]
    public function remove_teacher(int $id): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $this->directorService->removeTeacherFromInstitute($user, $id);
        return $this->json(['message' => 'Success']);
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute/awards/{id}', name: 'director_institute_award_edit', methods: ['PATCH'])]
    public function edit_award(int $id, Request $request): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $link = trim((string)($data['answerLink'] ?? ''));
        if (!$link) return $this->json(['error' => 'answerLink обязателен'], 400);
        try {
            $this->directorService->editInstituteAward($user, $id, $link);
            return $this->json(['message' => 'Updated']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute/awards/{id}', name: 'director_institute_award_delete', methods: ['DELETE'])]
    public function delete_award(int $id): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        try {
            $this->directorService->deleteInstituteAward($user, $id);
            return $this->json(['message' => 'Deleted']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    #[IsGranted('ROLE_DIRECTOR')]
    #[Route(path: '/institute/awards/{id}/freeze', name: 'director_institute_award_freeze', methods: ['PATCH'])]
    public function freeze_award(int $id, Request $request): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $active = (bool)($data['active'] ?? false);
        try {
            $this->directorService->setInstituteAwardActive($user, $id, $active);
            return $this->json(['message' => 'Updated']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

}
