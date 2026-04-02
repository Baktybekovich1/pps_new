<?php

namespace App\Controller\Director;

use App\Entity\Teacher;
use App\Service\Director\DirectorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_DIRECTOR')]
class DirectorController extends AbstractController
{
    public function __construct(private readonly DirectorService $directorService)
    {
    }
    #[Route(path: '/', name: 'find_all', methods: ['GET'])]
    public function get_all(): JsonResponse
    {
        return $this->json($this->directorService->findAllDirectors());
    }

    #[Route(path: '/institute', name: 'director_institute', methods: ['GET'])]
    public function get_institute(): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        return $this->json($this->directorService->getInstituteData($user));
    }

    #[Route(path: '/institute', name: 'director_institute_update', methods: ['PUT'])]
    public function update_institute(Request $request): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $this->directorService->updateInstitute($user, (int)$request->get('teacherTotal'));
        return $this->json(['message' => 'Success']);
    }

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

    #[Route(path: '/institute/teacher/{id}', name: 'director_institute_teacher_remove', methods: ['DELETE'])]
    public function remove_teacher(int $id): JsonResponse
    {
        /** @var Teacher $user */
        $user = $this->getUser();
        $this->directorService->removeTeacherFromInstitute($user, $id);
        return $this->json(['message' => 'Success']);
    }

}