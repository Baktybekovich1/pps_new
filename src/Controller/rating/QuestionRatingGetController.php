<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\QuestionGetDto;
use App\Dto\RatingDto\QuestionGetSubDto;
use App\Repository\QuestionSubtitleRepository;
use App\Repository\QuestionTitleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Rating')]
class QuestionRatingGetController extends AbstractController
{
    public function __construct(
        private readonly QuestionTitleRepository $questionTitleRepository,
        private readonly QuestionSubtitleRepository $questionSubtitleRepository
    )
    {
    }

    private function getQuestionsByStage(int $stageId): array
    {
        $titles = $this->questionTitleRepository->findBy(['stage' => $stageId]);
        $result = [];

        foreach ($titles as $title) {
            $subs = $this->questionSubtitleRepository->findBy(['title' => $title]);
            $subDtos = [];
            foreach ($subs as $sub) {
                $subDtos[] = new QuestionGetSubDto(
                    $sub->getId(),
                    $sub->getName()
                );
            }
            $result[] = new QuestionGetDto(
                $title->getId(),
                $title->getName(),
                $subDtos
            );
        }

        return $result;
    }

    #[Route('/question/get/awards', name: 'app_rating_get', methods: ['GET'])]
    public function getQuestionRating(): JsonResponse
    {
        return $this->json(['awards' => $this->getQuestionsByStage(2)]);
    }

    #[Route('/question/get/research', name: 'app_rating_get_research', methods: ['GET'])]
    public function getQuestionRatingResearch(): JsonResponse
    {
        return $this->json(['research' => $this->getQuestionsByStage(1)]);
    }

    #[Route('/question/get/innovative', name: 'app_rating_get_innovative', methods: ['GET'])]
    public function getQuestionRatingInnovative(): JsonResponse
    {
        return $this->json(['innovative' => $this->getQuestionsByStage(3)]);
    }

    #[Route('/question/get/social', name: 'app_rating_get_social', methods: ['GET'])]
    public function getQuestionRatingSocial(): JsonResponse
    {
        return $this->json(['social' => $this->getQuestionsByStage(4)]);
    }

}