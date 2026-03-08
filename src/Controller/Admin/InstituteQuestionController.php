<?php

namespace App\Controller\Admin;

use App\Dto\Institute\Question\SetInstituteQuestionSubtitleDto;
use App\Dto\Institute\Question\SetInstituteQuestionTitleDto;
use App\Service\Institute\InstituteQuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route(path: '/institute/question/title/{titleId}', methods: ['DELETE'])]
    public function removeTitle(Request $request): JsonResponse
    {
        return $this->json($this->instituteQuestionService->removeInstituteQuestionTitle($request->get('titleId')));
    }

    #[Route(path: '/institute/question/title/{titleId}', methods: ['PUT'])]
    public function editTitle(Request $request,#[MapRequestPayload] SetInstituteQuestionTitleDto $dto): JsonResponse
    {
        return $this->json($this->instituteQuestionService->editInstituteQuestionTitle($request->get('titleId'), $dto));
    }

    #[Route(path: '/institute/question/subtitle', methods: ['POST'])]
    public function setSubtitle(#[MapRequestPayload] SetInstituteQuestionSubtitleDto $dto): JsonResponse
    {
        return $this->json($this->instituteQuestionService->setInstituteQuestionSubtitle($dto));
    }

    #[Route(path: '/institute/question/subtitle/{subtitleId}', methods: ['DELETE'])]
    public function removeSubtitle(Request $request): JsonResponse
    {
        return $this->json($this->instituteQuestionService->removeInstituteQuestionSubtitle($request->get('subtitleId')));
    }
    #[Route(path: '/institute/question/subtitle/{subtitleId}', methods: ['PUT'])]
    public function editSubtitle(Request $request,SetInstituteQuestionSubtitleDto $dto): JsonResponse
    {
        return $this->json($this->instituteQuestionService->editInstituteQuestionSubtitle($request->get('subtitleId'),$dto));
    }




}