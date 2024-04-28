<?php

namespace App\Controller\admin\StageEdit;

use App\Repository\InnovativeEducationListRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\SocialActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StageEditGetSubtitleController extends AbstractController
{
    public function __construct(
        private readonly PersonalAwardsRepository          $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository  $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository    $socialActivitiesListRepository
    )
    {
    }

    #[Route('/stage/edit/award/subtitle/{titleId}', name: 'app_stage_edit_award_title')]
    public function award(Request $request): JsonResponse
    {
        $titles = $this->personalAwardsRepository->find($request->get('id'));
        $subtitles = $titles->getPersonalAwardsSubtitles();
        return $this->json([
            'subtitles' => $subtitles
        ]);
    }

    #[Route('/stage/edit/research/subtitle/{titleId}', name: 'app_stage_edit_research_title')]
    public function research(Request $request): JsonResponse
    {
        $title = $this->researchActivitiesListRepository->find($request->get('id'));
        $subtitles = $title->getResearchActivitiesSubtitles();
        return $this->json([
            'subtitles' => $subtitles
        ]);
    }

    #[Route('/stage/edit/innovative/subtitle/{titleId}', name: 'app_stage_edit_innovative_title')]
    public function innovative(Request $request): JsonResponse
    {
        $title = $this->innovativeEducationListRepository->find($request->get('id'));
        $subtitles = $title->getInnovativeEducationSubtitles();
        return $this->json([
            'subtitles' => $subtitles
        ]);
    }

    #[Route('/stage/edit/social/subtitle/{titleId}', name: 'app_stage_edit_social_title')]
    public function social(Request $request): JsonResponse
    {
        $title = $this->socialActivitiesListRepository->find($request->get('id'));
        $subtitles = $title->getSocialActivitiesSubtitles();
        return $this->json([
            'subtitles' => $subtitles
        ]);
    }
}
