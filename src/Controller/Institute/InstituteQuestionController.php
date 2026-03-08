<?php

namespace App\Controller\Institute;

use App\Service\Instiute\InstituteService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Institute', description: 'Institute Question')]
class InstituteQuestionController extends AbstractController
{
    public function __construct(private readonly InstituteService $instituteService)
    {
    }

    #[Route(path: '/question', name: 'institute_question', methods: ['GET'])]
    public function question(): JsonResponse
    {
        return $this->json($this->instituteService->getInstituteQuestion());
    }

}