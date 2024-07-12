<?php

namespace App\Controller\admin\StageEdit;

use App\Dto\AdminStagesEdit\StageDeleteTitleDto;
use App\Dto\AdminStagesEdit\StagesDeleteSubtitleDto;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\InnovativeEducationSubtitleRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\PersonalAwardsSubtitleRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\SocialActivitiesListRepository;
use App\Repository\SocialActivitiesSubtitleRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class TitleDeleteController extends AbstractController
{
    public function __construct(
        private readonly PersonalAwardsRepository             $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository     $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository    $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository       $socialActivitiesListRepository,
        private readonly PersonalAwardsSubtitleRepository     $personalAwardsSubtitleRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly ResearchActivitiesSubtitleRepository $researchActivitiesSubtitleRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository, private readonly InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository, private readonly UserInnovativeEducationRepository $userInnovativeEducationRepository, private readonly SocialActivitiesSubtitleRepository $socialActivitiesSubtitleRepository, private readonly UserSocialActivitiesRepository $userSocialActivitiesRepository

    )
    {
    }

    #[Route('/title/delete/awards', name: 'app_title_delete_award', methods: ['DELETE'])]
    public function index(#[MapRequestPayload] StageDeleteTitleDto $dto): JsonResponse
    {
        $title = $this->personalAwardsRepository->find($dto->titleId);
        $subs = $this->personalAwardsSubtitleRepository->findBy(['title' => $title]);
        foreach ($subs as $sub) {
            $awards = $this->userPersonalAwardsRepository->findBy(['subtitle' => $sub]);
            foreach ($awards as $award) {
                $this->userPersonalAwardsRepository->remove($award);
            }
            $this->personalAwardsSubtitleRepository->remove($sub);
        }
        $this->personalAwardsRepository->remove($title);

        return $this->json(['Успешно удалено']);
    }

    #[Route('/title/delete/research', name: 'app_title_delete_research', methods: ['DELETE'])]
    public function research(#[MapRequestPayload] StageDeleteTitleDto $dto): JsonResponse
    {
        $title = $this->researchActivitiesListRepository->find($dto->titleId);
        $subs = $this->researchActivitiesSubtitleRepository->findBy(['title' => $title]);
        foreach ($subs as $sub) {
            $awards = $this->userResearchActivitiesListRepository->findBy(['subtitle' => $sub]);
            foreach ($awards as $award) {
                $this->userResearchActivitiesListRepository->remove($award);
            }
            $this->researchActivitiesSubtitleRepository->remove($sub);
        }
        $this->researchActivitiesListRepository->remove($title);

        return $this->json(['Успешно удалено']);
    }

    #[Route('/title/delete/innovative', name: 'app_title_delete_innovative', methods: ['DELETE'])]
    public function innovative(#[MapRequestPayload] StageDeleteTitleDto $dto): JsonResponse
    {
        $title = $this->innovativeEducationListRepository->find($dto->titleId);
        $subs = $this->innovativeEducationSubtitleRepository->findBy(['title' => $title]);
        foreach ($subs as $sub) {
            $awards = $this->userInnovativeEducationRepository->findBy(['subtitle' => $sub]);
            foreach ($awards as $award) {
                $this->userInnovativeEducationRepository->remove($award);
            }
            $this->innovativeEducationSubtitleRepository->remove($sub);
        }
        $this->innovativeEducationListRepository->remove($title);

        return $this->json(['Успешно удалено']);
    }

    #[Route('/title/delete/social', name: 'app_title_delete_social', methods: ['DELETE'])]
    public function social(#[MapRequestPayload] StageDeleteTitleDto $dto): JsonResponse
    {
        $title = $this->socialActivitiesListRepository->find($dto->titleId);
        $subs = $this->socialActivitiesSubtitleRepository->findBy(['title' => $title]);
        foreach ($subs as $sub) {
            $awards = $this->userSocialActivitiesRepository->findBy(['subtitle' => $sub]);
            foreach ($awards as $award) {
                $this->userSocialActivitiesRepository->remove($award);
            }
            $this->socialActivitiesSubtitleRepository->remove($sub);
        }
        $this->socialActivitiesListRepository->remove($title);

        return $this->json(['Успешно удалено']);
    }
}
