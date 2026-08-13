<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\QuestionGetDto;
use App\Dto\RatingDto\QuestionGetSubDto;
use App\Repository\QuestionSubtitleRepository;
use App\Repository\QuestionTitleRepository;
use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Rating')]
class QuestionRatingGetController extends AbstractController
{
    public function __construct(
        private readonly StageRepository            $stageRepository,
        private readonly QuestionTitleRepository    $questionTitleRepository,
        private readonly QuestionSubtitleRepository $questionSubtitleRepository
    )
    {
    }

    /**
     * Every question of one organization, across all its stages.
     *
     * Replaces four routes that each answered for a stage id written into the
     * controller — /question/get/awards returned stage 2, which is research,
     * and /question/get/social pointed at a stage that does not exist. Stages
     * are rows an admin can add and reorder, so no id belongs in here.
     *
     * The organization comes from the URL: this serves a page about one
     * organization, and reading it from the visitor instead would show them
     * their own questions while they are looking at somebody else's rating.
     */
    #[Route('/organization/{orgId}/questions', name: 'app_organization_questions', methods: ['GET'])]
    public function questions(int $orgId): JsonResponse
    {
        $questions = [];

        foreach ($this->stageRepository->findAll() as $stage) {
            foreach ($this->questionTitleRepository->findBy(['stage' => $stage]) as $title) {
                $subtitles = [];
                foreach ($this->questionSubtitleRepository->findBy(['title' => $title]) as $subtitle) {
                    $subtitles[] = new QuestionGetSubDto($subtitle->getId(), $subtitle->getName());
                }

                $questions[] = [
                    'stageId' => $stage->getId(),
                    'stageName' => $stage->getName(),
                    'question' => new QuestionGetDto($title->getId(), $title->getName(), $subtitles),
                ];
            }
        }

        return $this->json(['questions' => $questions]);
    }
}
