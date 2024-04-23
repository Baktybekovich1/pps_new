<?php

namespace App\Controller\admin;

use App\Dto\AwardsEditDto\TitleAndSubSetDto;
use App\Entity\PersonalAwardsSubtitle;
use App\Repository\PersonalAwardsRepository;
use App\Repository\PersonalAwardsSubtitleRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class TitleEditController extends AbstractController
{
    public function __construct(
        private PersonalAwardsRepository         $personalAwardsRepository,
        private PersonalAwardsSubtitleRepository $personalAwardsSubtitleRepository,
        private ResearchActivitiesListRepository $researchActivitiesListRepository,
        private ResearchActivitiesSubtitleRepository $researchActivitiesSubtitleRepository
    )
    {
    }

    #[Route('/award/title/edit', name: 'app_title_edit', methods: ['PUT'])]
    public function index(#[MapRequestPayload] TitleAndSubSetDto $dto): JsonResponse
    {

        $title = $this->personalAwardsRepository->find($dto->titleId);
        $title->setName($dto->titleName);

        foreach ($dto->subtitles as $subtitle) {
            $sub = $this->personalAwardsSubtitleRepository->find($subtitle['subId']);

            $sub->setName($subtitle['subName']);
            $title->addPersonalAwardsSubtitle($sub);
        }
        $this->personalAwardsRepository->save($title);

        return $this->json([
            "Success"
        ]);
    }

//    #[Route('/research/title/edit', name: 'app_research_edit', methods: ['PUT'])]
//    public function research(#[MapRequestPayload] TitleAndSubSetDto $dto): JsonResponse
//    {
//
//        $title = $this->researchActivitiesListRepository->find($dto->titleId);
//        $title->setName($dto->titleName);
//
//        foreach ($dto->subtitles as $subtitle) {
//            $sub = $this->personalAwardsSubtitleRepository->find($subtitle['subId']);
//            $sub->setName($subtitle['subName']);
//            $title->addResearchActivitiesSubtitle($sub);
//        }
//        $this->researchActivitiesListRepository->save($title);
//
//        return $this->json([
//            "Success"
//        ]);
//    }


}
