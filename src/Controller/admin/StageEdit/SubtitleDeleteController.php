<?php

namespace App\Controller\admin\StageEdit;

use App\Dto\AdminStagesEdit\StagesDeleteSubtitleDto;
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
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class SubtitleDeleteController extends AbstractController
{
    public function __construct(
        private PersonalAwardsRepository                       $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository      $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository     $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository        $socialActivitiesListRepository,
        private readonly PersonalAwardsSubtitleRepository      $personalAwardsSubtitleRepository,
        private readonly ResearchActivitiesSubtitleRepository  $researchActivitiesSubtitleRepository,
        private readonly InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository,
        private readonly SocialActivitiesSubtitleRepository    $socialActivitiesSubtitleRepository
    )
    {
    }

    #[Route('/subtitle/delete/award', name: 'app_subtitle_delete_award', methods: ['DELETE'])]
    public function index(#[MapRequestPayload] StagesDeleteSubtitleDto $dto): JsonResponse
    {
        $award = $this->personalAwardsSubtitleRepository->find($dto->id);
        $this->personalAwardsRepository->remove($award);

        return $this->json(['message']);
    }


    #[Route('/subtitle/delete/research', name: 'app_subtitle_delete_research', methods: ['DELETE'])]
    public function research(#[MapRequestPayload] StagesDeleteSubtitleDto $dto): JsonResponse
    {
        $award = $this->researchActivitiesSubtitleRepository->find($dto->id);
        $this->researchActivitiesSubtitleRepository->remove($award);

        return $this->json(['message']);
    }

}
