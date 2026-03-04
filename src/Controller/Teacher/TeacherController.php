<?php

namespace App\Controller\Teacher;

use App\Service\Teacher\TeacherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TeacherController extends AbstractController
{
    public function __construct(
        private readonly TeacherService $teacherService,
    )
    {
    }

    #[Route(path: '/', name: 'teachers_list', methods: ['GET'])]
    public function teachers(): JsonResponse
    {
        return $this->json($this->teacherService->getAllTeachers());
    }


}