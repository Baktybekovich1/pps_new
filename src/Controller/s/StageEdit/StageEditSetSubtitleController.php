<?php

namespace App\Controller\s\StageEdit;

use App\Dto\AdminStagesEdit\StagesEditSubtitleAddDto;
use App\Dto\AdminStagesEdit\StagesEditTitleAddDto;
use App\Entity\InnovativeEducationList;
use App\Entity\InnovativeEducationSubtitle;
use App\Entity\PersonalAwards;
use App\Entity\PersonalAwardsSubtitle;
use App\Entity\ResearchActivitiesList;
use App\Entity\ResearchActivitiesSubtitle;
use App\Entity\SocialActivitiesList;
use App\Entity\SocialActivitiesSubtitle;
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

class StageEditSetSubtitleController extends AbstractController
{
    public function __construct(
        private PersonalAwardsRepository                   $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository  $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository    $socialActivitiesListRepository,
        private PersonalAwardsSubtitleRepository           $personalAwardsSubtitleRepository,
        private ResearchActivitiesSubtitleRepository       $researchActivitiesSubtitleRepository,
        private InnovativeEducationSubtitleRepository      $innovativeEducationSubtitleRepository,
        private SocialActivitiesSubtitleRepository         $socialActivitiesSubtitleRepository

    )
    {
    }

    #[Route('/stage/edit/award/sub/add', name: 'app_stage_edit_award_sub_add', methods: ['POST'])]
    public function award(#[MapRequestPayload] StagesEditSubtitleAddDto $dto): JsonResponse
    {
        if (isset($dto->id)) {
            $sub = $this->personalAwardsSubtitleRepository->find($dto->id);
        } else {
            $sub = new PersonalAwardsSubtitle();
        }
        $title = $this->personalAwardsRepository->find($dto->titleId);
        if ($title != null) {
            $sub->setName($dto->name);
            $sub->setPoints($dto->points);
            $title->addPersonalAwardsSubtitle($sub);
            $this->personalAwardsRepository->save($title);
            return $this->json(["Success"]);
        } else {
            return $this->json(["Error! Title ещё не создан!"]);
        }

    }

    #[Route('/stage/edit/research/sub/add', name: 'app_stage_edit_research_sub_add', methods: ['POST'])]
    public function research(#[MapRequestPayload] StagesEditSubtitleAddDto $dto): JsonResponse
    {
        if (isset($dto->id)) {
            $sub = $this->researchActivitiesSubtitleRepository->find($dto->id);
        } else {
            $sub = new ResearchActivitiesSubtitle();
        }
        $title = $this->researchActivitiesListRepository->find($dto->titleId);
        if ($title == null) {
            $sub->setName($dto->name);
            $sub->setPoints($dto->points);
            $title->addResearchActivitiesSubtitle($sub);
            $this->researchActivitiesListRepository->save($title);
            return $this->json(["Success"]);
        } else {
            return $this->json(["Error! Title ещё не создан!"]);
        }

    }

    #[Route('/stage/edit/innovative/sub/add', name: 'app_stage_edit_innovative_sub_add', methods: ['POST'])]
    public function innovative(#[MapRequestPayload] StagesEditSubtitleAddDto $dto): JsonResponse
    {
        if (isset($dto->id)) {
            $sub = $this->innovativeEducationSubtitleRepository->find($dto->id);
        } else {
            $sub = new InnovativeEducationSubtitle();
        }
        $title = $this->innovativeEducationListRepository->find($dto->titleId);
        if ($title == null) {
            $sub->setName($dto->name);
            $sub->setPoints($dto->points);
            $title->addInnovativeEducationSubtitle($sub);
            $this->innovativeEducationListRepository->save($title);
            return $this->json(["Success"]);
        } else {
            return $this->json(["Error! Title ещё не создан!"]);
        }
    }

    #[Route('/stage/edit/social/sub/add', name: 'app_stage_edit_social_sub_add', methods: ['POST'])]
    public function social(#[MapRequestPayload] StagesEditSubtitleAddDto $dto): JsonResponse
    {
        if (isset($dto->id)) {
            $sub = $this->socialActivitiesSubtitleRepository->find($dto->id);
        } else {
            $sub = new SocialActivitiesSubtitle();
        }
        $title = $this->socialActivitiesListRepository->find($dto->titleId);
        if ($title == null) {
            $sub = new SocialActivitiesSubtitle();
            $sub->setName($dto->name);
            $sub->setPoints($dto->points);
            $title->addSocialActivitiesSubtitle($sub);
            $this->socialActivitiesListRepository->save($title);
            return $this->json(["Success"]);
        } else {
            return $this->json(["Error! Title ещё не создан!"]);
        }
    }
}
