<?php

namespace App\Controller\s\StageEdit;

use App\Dto\AdminStagesEdit\StagesDeleteSubtitleDto;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\InnovativeEducationSubtitleRepository;
use App\Repository\PersonalAwardsSubtitleRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\SocialActivitiesListRepository;
use App\Repository\SocialActivitiesSubtitleRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class SubtitleDeleteController extends AbstractController
{
    public function __construct(
        private readonly PersonalAwardsSubtitleRepository      $personalAwardsSubtitleRepository,
        private readonly ResearchActivitiesSubtitleRepository  $researchActivitiesSubtitleRepository,
        private readonly InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository,
        private readonly SocialActivitiesSubtitleRepository    $socialActivitiesSubtitleRepository,
        private readonly UserPersonalAwardsRepository          $userPersonalAwardsRepository,
        private readonly UserResearchActivitiesListRepository  $userResearchActivitiesListRepository,
        private readonly InnovativeEducationListRepository     $innovativeEducationListRepository,
        private readonly UserInnovativeEducationRepository     $userInnovativeEducationRepository,
        private readonly SocialActivitiesListRepository        $socialActivitiesListRepository,
        private readonly UserSocialActivitiesRepository        $userSocialActivitiesRepository)
    {
    }

    #[Route('/subtitle/delete/awards', name: 'subtitle_delete_awards', methods: ['DELETE'])]
    public function awardDelete(#[MapRequestPayload] StagesDeleteSubtitleDto $dto): JsonResponse
    {
        $sub = $this->personalAwardsSubtitleRepository->find($dto->subId);
        $awards = $this->userPersonalAwardsRepository->findBy(['subtitle' => $sub]);
        foreach ($awards as $award) {
            $this->userPersonalAwardsRepository->remove($award);
        }
        $this->personalAwardsSubtitleRepository->remove($sub);
        return $this->json(['Успешно удалено']);
    }

    #[Route('/subtitle/delete/research', name: 'subtitle_delete_research', methods: ['DELETE'])]
    public function researchDelete(#[MapRequestPayload] StagesDeleteSubtitleDto $dto): JsonResponse
    {
        $sub = $this->researchActivitiesSubtitleRepository->find($dto->subId);
        $research = $this->researchActivitiesSubtitleRepository->findBy(['subtitle' => $sub]);
        foreach ($research as $item) {
            $this->userResearchActivitiesListRepository->remove($item);
        }
        $this->researchActivitiesSubtitleRepository->remove($sub);
        return $this->json(['Успешно удалено']);
    }

    #[Route('/subtitle/delete/innovative', name: 'subtitle_delete_innovative', methods: ['DELETE'])]
    public function innovativeDelete(#[MapRequestPayload] StagesDeleteSubtitleDto $dto): JsonResponse
    {
        $sub = $this->innovativeEducationSubtitleRepository->find($dto->subId);
        $research = $this->innovativeEducationListRepository->findBy(['subtitle' => $sub]);
        foreach ($research as $item) {
            $this->userInnovativeEducationRepository->remove($item);
        }
        $this->innovativeEducationSubtitleRepository->remove($sub);
        return $this->json(['Успешно удалено']);
    }

    #[Route('/subtitle/delete/social', name: 'subtitle_delete_social', methods: ['DELETE'])]
    public function socialDelete(#[MapRequestPayload] StagesDeleteSubtitleDto $dto): JsonResponse
    {
        $sub = $this->socialActivitiesSubtitleRepository->find($dto->subId);
        $research = $this->socialActivitiesListRepository->findBy(['subtitle' => $sub]);
        foreach ($research as $item) {
            $this->userSocialActivitiesRepository->remove($item);
        }
        $this->socialActivitiesSubtitleRepository->remove($sub);
        return $this->json(['Успешно удалено']);
    }

}