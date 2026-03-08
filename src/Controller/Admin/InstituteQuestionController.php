<?php

namespace App\Controller\Admin;

use App\Dto\Institute\Question\SetInstituteQuestionSubtitleDto;
use App\Dto\Institute\Question\SetInstituteQuestionTitleDto;
use App\Service\Institute\InstituteQuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class InstituteQuestionController extends AbstractController
{
    public function __construct(
        private InstituteQuestionService $instituteQuestionService
    )
    {
    }
    #[Route(path: '/institute/question/title', methods: ['POST'])]
    public function setTitle(#[MapRequestPayload] SetInstituteQuestionTitleDto $dto): JsonResponse
    {
        return $this->json($this->instituteQuestionService->setInstituteQuestionTitle($dto));
    }

    #[Route(path: '/institute/question/subtitle', methods: ['POST'])]
    public function setSubtitle(#[MapRequestPayload] SetInstituteQuestionSubtitleDto $dto): JsonResponse
    {
        return $this->json($this->instituteQuestionService->setInstituteQuestionSubtitle($dto));
    }



}