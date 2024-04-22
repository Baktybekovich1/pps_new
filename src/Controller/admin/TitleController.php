<?php

namespace App\Controller\admin;

use App\Dto\AwardsEditDto\AwardsTitleGetDto;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\SocialActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TitleController extends AbstractController
{
    public function __construct(
        private readonly PersonalAwardsRepository          $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository  $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository    $socialActivitiesListRepository
    )
    {
    }

    #[Route('/award/title', name: 'app_award_title')]
    public function award(): JsonResponse
    {
        $awards = $this->personalAwardsRepository->findAll();
        $get = [];
        foreach ($awards as $award) {
            $get[] = new AwardsTitleGetDto(
                $award->getId(),
                $award->getName()
            );
        }
        return $this->json([
            'awards' => $get
        ]);
    }

    #[Route('/research/title', name: 'app_research_title')]
    public function research(): JsonResponse
    {
        $researchs = $this->researchActivitiesListRepository->findAll();
        $get = [];
        foreach ($researchs as $research) {
            $get[] = new AwardsTitleGetDto(
                $research->getId(),
                $research->getName()
            );
        }
        return $this->json([
            'research' => $get
        ]);
    }

    #[Route('/innovative/title', name: 'app_innovative_title')]
    public function innovative(): JsonResponse
    {
        $innovatives = $this->innovativeEducationListRepository->findAll();
        $get = [];
        foreach ($innovatives as $innovative) {
            $get[] = new AwardsTitleGetDto(
                $innovative->getId(),
                $innovative->getName()
            );
        }
        return $this->json([
            'innovative' => $get
        ]);
    }

    #[Route('/social/title', name: 'app_social_title')]
    public function social(): JsonResponse
    {
        $socials = $this->socialActivitiesListRepository->findAll();
        $get = [];
        foreach ($socials as $social) {
            $get[] = new AwardsTitleGetDto(
                $social->getId(),
                $social->getName()
            );
        }
        return $this->json([
            'social' => $get
        ]);
    }


}
