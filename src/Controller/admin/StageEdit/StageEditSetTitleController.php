<?php

namespace App\Controller\admin\StageEdit;

use App\Dto\AdminStagesEdit\StagesEditTitleAddDto;
use App\Entity\InnovativeEducationList;
use App\Entity\PersonalAwards;
use App\Entity\ResearchActivitiesList;
use App\Entity\SocialActivitiesList;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\SocialActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class StageEditSetTitleController extends AbstractController
{
    public function __construct(
        private PersonalAwardsRepository                   $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository  $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository    $socialActivitiesListRepository
    )
    {
    }

    #[Route('/stage/edit/award/title/add', name: 'app_stage_edit_award_title_add', methods: ['POST'])]
    public function award(#[MapRequestPayload] StagesEditTitleAddDto $dto): JsonResponse
    {
        $text = $dto->title;
        if ($text != null) {
            $title = new PersonalAwards();
            $title->setName($text);
            $this->personalAwardsRepository->save($title);
            return $this->json([
                'Success'
            ]);
        } else {
            return $this->json([
                "Error"
            ]);
        }


    }

    #[Route('/stage/edit/research/title/add', name: 'app_stage_edit_research_title_add', methods: ['POST'])]
    public function research(#[MapRequestPayload] StagesEditTitleAddDto $dto): JsonResponse
    {
        $text = $dto->title;
        if ($text != null) {
            $title = new ResearchActivitiesList();
            $title->setName($text);
            $this->researchActivitiesListRepository->save($title);
            return $this->json([
                'Success'
            ]);
        } else {
            return $this->json([
                "Error"
            ]);
        }

    }

    #[Route('/stage/edit/innovative/title/add', name: 'app_stage_edit_innovative_title_add', methods: ['POST'])]
    public function innovative(#[MapRequestPayload] StagesEditTitleAddDto $dto): JsonResponse
    {
        $text = $dto->title;
        if ($text != null) {
            $title = new InnovativeEducationList();
            $title->setName($text);
            $this->innovativeEducationListRepository->save($title);
            return $this->json([
                'Success'
            ]);
        } else {
            return $this->json([
                "Error"
            ]);
        }
    }

    #[Route('/stage/edit/social/title/add', name: 'app_stage_edit_social_title_add', methods: ['POST'])]
    public function social(#[MapRequestPayload] StagesEditTitleAddDto $dto): JsonResponse
    {
        $text = $dto->title;
        if ($text != null) {
            $title = new SocialActivitiesList();
            $title->setName($text);
            $this->socialActivitiesListRepository->save($title);
            return $this->json([
                'Success'
            ]);
        } else {
            return $this->json([
                "Error"
            ]);
        }
    }
}
