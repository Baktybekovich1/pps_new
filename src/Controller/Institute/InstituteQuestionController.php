<?php

namespace App\Controller\Institute;

use App\Service\Institute\InstituteQuestionService;
use App\Service\Institute\InstituteService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Institute', description: 'Institute Question')]
class InstituteQuestionController extends AbstractController
{
    public function __construct(private readonly InstituteQuestionService $instituteQuestionService)
    {
    }


    #[Route(path: '/question/title', name: 'question_title', methods: ['GET'])]
    public function getTitles(): JsonResponse
    {
        return $this->json($this->instituteQuestionService->getTitles());
    }
    #[Route(path: '/question/subtitle', name: 'question_subtitle', methods: ['GET'])]
    public function getSubtitles(): JsonResponse
    {
        return $this->json($this->instituteQuestionService->getSubtitles());
    }
    


}