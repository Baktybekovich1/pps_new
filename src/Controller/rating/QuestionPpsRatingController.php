<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\QuestionPPSRatingDto;
use App\Repository\PersonalAwardsRepository;
use App\Repository\PersonalAwardsSubtitleRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class QuestionPpsRatingController extends AbstractController
{


    public function __construct(
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly UserResearchActivitiesListRepository $userActivitiesListsRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserRepository                       $userRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly PersonalAwardsRepository             $personalAwardsRepository,
        private readonly PersonalAwardsSubtitleRepository $personalAwardsSubtitleRepository
    )
    {
    }

    #[Route('/question/awards/{titleId}/{subId}')]
    public function getPps(Request $request): JsonResponse
    {
        if ($request->get('titleId') != null) {
            $title = $this->personalAwardsRepository->find($request->get('titleId'));
            $userInfos = $this->userInfoRepository->findAll();
            $pps = [];

            foreach ($userInfos as $userInfo) {
                $points = 0;
                $user = $userInfo->getUser();
                if ($userInfo->getInstitutions()->getUniversity() != 'МУИТ') {
                    continue;
                }

                $personalAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user]);
                foreach ($personalAwards as $personalAward) {
                    if ($personalAward->getSubtitle()->getTitle() !== $title) {
                        continue;
                    }
                    if ($request->get('subId') != null) {
                        $sub = $this->personalAwardsSubtitleRepository->find($request->get('subId'));
                        if ($personalAward->getSubtitle() !== $sub) {
                            continue;
                        }
                    }
                    $points += $personalAward->getSubtitle()->getPoints();
                }
                $pps[] = new QuestionPPSRatingDto(
                    $user->getId(),
                    $userInfo->getName(),
                    $userInfo->getInstitutions()->getName(),
                    $points
                );

            }
        }
        return $this->json($pps);

    }

}