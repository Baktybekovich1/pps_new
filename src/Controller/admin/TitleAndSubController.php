<?php

namespace App\Controller\admin;

use App\Dto\AwardsEditDto\AwardsTitleGetDto;
use App\Dto\AwardsEditDto\SubtitleGetDto;
use App\Dto\AwardsEditDto\TitleAndSubGetDto;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\SocialActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TitleAndSubController extends AbstractController
{
    public function __construct(
        private readonly PersonalAwardsRepository          $personalAwardsRepository,
        private readonly ResearchActivitiesListRepository  $researchActivitiesListRepository,
        private readonly InnovativeEducationListRepository $innovativeEducationListRepository,
        private readonly SocialActivitiesListRepository    $socialActivitiesListRepository
    )
    {
    }

    #[Route('/award/sub', name: 'app_award_sub')]
    public function award(): JsonResponse
    {
        $awards = $this->personalAwardsRepository->findAll();
        $get = [];

        foreach ($awards as $award) {
            $sub = [];
            foreach ($award->getPersonalAwardsSubtitles() as $item) {
                $sub[] = new SubtitleGetDto(
                    $item->getId(),
                    $item->getName()
                );
            }
            $get[] = new TitleAndSubGetDto(
                $award->getId(),
                $award->getName(),
                $sub
            );

        }
        return $this->json([
            'awards' => $get
        ]);
    }

    #[Route('/research/sub', name: 'app_research_sub')]
    public function research(): JsonResponse
    {
        $researchs = $this->researchActivitiesListRepository->findAll();
        $get = [];
        foreach ($researchs as $research) {
            $sub = [];
            foreach ($research->getResearchActivitiesSubtitles() as $subtitle) {
                $sub[] = new SubtitleGetDto(
                    $subtitle->getId(),
                    $subtitle->getName()
                );
            }
            $get[] = new TitleAndSubGetDto(
                $research->getId(),
                $research->getName(),
                $sub
            );
        }
        return $this->json([
            'research' => $get
        ]);
    }

    #[Route('/innovative/sub', name: 'app_innovative_sub')]
    public function innovative(): JsonResponse
    {
        $innovatives = $this->innovativeEducationListRepository->findAll();
        $get = [];
        foreach ($innovatives as $innovative) {
            $sub = [];
            foreach ($innovative->getInnovativeEducationSubtitles() as $subtitle) {
                $sub[] = new SubtitleGetDto(
                    $subtitle->getId(),
                    $subtitle->getName()
                );
            }
            $get[] = new TitleAndSubGetDto(
                $innovative->getId(),
                $innovative->getName(),
                $sub
            );
        }
        return $this->json([
            'innovative' => $get
        ]);
    }

    #[Route('/social/sub', name: 'app_social_sub')]
    public function social(): JsonResponse
    {
        $socials = $this->socialActivitiesListRepository->findAll();
        $get = [];
        foreach ($socials as $social) {
            $sub = [];
            foreach ($social->getSocialActivitiesSubtitles() as $subtitle) {
                $sub[] = new SubtitleGetDto(
                    $subtitle->getId(),
                    $subtitle->getName()
                );
            }
            $get[] = new TitleAndSubGetDto(
                $social->getId(),
                $social->getName(),
                $sub
            );
        }
        return $this->json([
            'social' => $get
        ]);
    }


}
