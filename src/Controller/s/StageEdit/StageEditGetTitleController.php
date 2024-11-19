<?php

namespace App\Controller\s\StageEdit;

use App\Dto\UserStagesGet\TitleGetDto;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\SocialActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class StageEditGetTitleController extends AbstractController
{
    public function __construct(
        private readonly PersonalAwardsRepository          $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository  $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository    $socialActivitiesListRepository
    )
    {
    }

    #[Route('/stage/edit/award/title', name: 'app_stage_edit_award_title')]
    public function award(): JsonResponse
    {
        $titles = $this->personalAwardsRepository->findAll();
        $getTitle = [];
        foreach ($titles as $title) {
            $getTitle[] = new TitleGetDto(
                $title->getId(),
                $title->getName()
            );
        }
        return $this->json([
            'titles' => $getTitle
        ]);
    }

    #[Route('/stage/edit/research/title', name: 'app_stage_edit_research_title')]
    public function research(): JsonResponse
    {
        $titles = $this->researchActivitiesListRepository->findAll();
        $getTitle = [];
        foreach ($titles as $title) {
            $getTitle[] = new TitleGetDto(
                $title->getId(),
                $title->getName()
            );
        }
        return $this->json([
            'titles' => $getTitle
        ]);
    }

    #[Route('/stage/edit/innovative/title', name: 'app_stage_edit_innovative_title')]
    public function innovative(): JsonResponse
    {
        $titles = $this->innovativeEducationListRepository->findAll();
        $getTitle = [];
        foreach ($titles as $title) {
            $getTitle[] = new TitleGetDto(
                $title->getId(),
                $title->getName()
            );
        }
        return $this->json([
            'titles' => $getTitle
        ]);
    }

    #[Route('/stage/edit/social/title', name: 'app_stage_edit_social_title')]
    public function social(): JsonResponse
    {
        $titles = $this->socialActivitiesListRepository->findAll();
        $getTitle = [];
        foreach ($titles as $title) {
            $getTitle[] = new TitleGetDto(
                $title->getId(),
                $title->getName()
            );
        }
        return $this->json([
            'titles' => $getTitle
        ]);
    }
}
