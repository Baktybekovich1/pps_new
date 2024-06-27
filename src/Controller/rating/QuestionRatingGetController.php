<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\QuestionGetDto;
use App\Dto\RatingDto\QuestionGetSubDto;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\InnovativeEducationSubtitleRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\PersonalAwardsSubtitleRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\SocialActivitiesListRepository;
use App\Repository\SocialActivitiesSubtitleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class QuestionRatingGetController extends AbstractController
{
    public function __construct(private readonly PersonalAwardsRepository $personalAwardsRepository, private readonly PersonalAwardsSubtitleRepository $personalAwardsSubtitleRepository, private readonly ResearchActivitiesListRepository $researchActivitiesListRepository, private readonly ResearchActivitiesSubtitleRepository $researchActivitiesSubtitleRepository, private readonly InnovativeEducationListRepository $innovativeEducationListRepository, private readonly InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository, private readonly SocialActivitiesListRepository $socialActivitiesListRepository, private readonly SocialActivitiesSubtitleRepository $socialActivitiesSubtitleRepository)
    {
    }

    #[Route('/question/get/awards', name: 'app_rating_get', methods: ['GET'])]
    public function getQuestionRating(): JsonResponse
    {
        $awards = $this->personalAwardsRepository->findAll();

        $as = [];

        foreach ($awards as $award) {
            $sub = $this->personalAwardsSubtitleRepository->findBy(['title' => $award]);
            $os = [];
            foreach ($sub as $item) {

                $os[] = new QuestionGetSubDto(
                    $item->getId(),
                    $item->getName()
                );
            }
            $as[] = new QuestionGetDto(
                $award->getId(),
                $award->getName(),
                $os
            );
        }

        return $this->json(['awards' => $as]);
    }


    #[Route('/question/get/research', name: 'app_rating_get_research', methods: ['GET'])]
    public function getQuestionRatingResearch(): JsonResponse
    {
        $awards = $this->researchActivitiesListRepository->findAll();

        $as = [];
        foreach ($awards as $award) {
            $sub = $this->researchActivitiesSubtitleRepository->findBy(['category' => $award]);
            $os = [];

            foreach ($sub as $item) {

                $os[] = new QuestionGetSubDto(
                    $item->getId(),
                    $item->getName()
                );
            }
            $as[] = new QuestionGetDto(
                $award->getId(),
                $award->getName(),
                $os
            );
        }

        return $this->json(['research' => $as]);
    }

    #[Route('/question/get/innovative', name: 'app_rating_get_innovative', methods: ['GET'])]
    public function getQuestionRatingInnovative(): JsonResponse
    {
        $awards = $this->innovativeEducationListRepository->findAll();

        $as = [];
        foreach ($awards as $award) {
            $sub = $this->innovativeEducationSubtitleRepository->findBy(['title' => $award]);
            $os = [];

            foreach ($sub as $item) {

                $os[] = new QuestionGetSubDto(
                    $item->getId(),
                    $item->getName()
                );
            }
            $as[] = new QuestionGetDto(
                $award->getId(),
                $award->getName(),
                $os
            );
        }

        return $this->json(['innovative' => $as]);
    }

    #[Route('/question/get/social', name: 'app_rating_get_social', methods: ['GET'])]
    public function getQuestionRatingSocial(): JsonResponse
    {
        $awards = $this->socialActivitiesListRepository->findAll();

        $as = [];
        foreach ($awards as $award) {
            $sub = $this->socialActivitiesSubtitleRepository->findBy(['title' => $award]);
            $os = [];
            foreach ($sub as $item) {
                $os[] = new QuestionGetSubDto(
                    $item->getId(),
                    $item->getName()
                );
            }
            $as[] = new QuestionGetDto(
                $award->getId(),
                $award->getName(),
                $os
            );
        }

        return $this->json(['social' => $as]);
    }

}