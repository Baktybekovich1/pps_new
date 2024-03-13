<?php

namespace App\Controller\rating;

use App\Dto\PpsProgressDto;
use App\Dto\PpsRatingDto;
use App\Entity\UserPersonalAwards;
use App\Entity\UserResearchActivitiesList;
use App\Repository\PersonalAwardsRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserProgressRepository;
use App\Repository\UserResearchActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PpsRatingController extends AbstractController
{
    public function __construct(
        private UserInfoRepository                   $userInfoRepository,
        private UserResearchActivitiesListRepository $userActivitiesListsRepository,
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
    )
    {
    }

    #[Route('/pps', name: 'app_pps_rating')]
    public function index(): JsonResponse
    {
//        $pps = $this->userInfoRepository->findOneBy(['userId' => 14]);
        $activities = $this->userActivitiesListsRepository->findAll();
        $userProgress = $this->userPersonalAwardsRepository->findAll();

        $pps = [];
        foreach ($activities as $activity) {
            foreach ($userProgress as $progress) {
                if (isset($pps[$activity->getUser()->getId()])) {
                    /** @var PpsRatingDto $item */
                    $item = $pps[$activity->getUser()->getId()];

                    $item->uralPoints += $activity->getPoints();
                    $item->progressPoints += $progress->getPoints();

                } else {
                    $pps[$activity->getUser()->getId()] = new PpsRatingDto(
                        $activity->getUser()->getUsername(),
                        $activity->getPoints(),
                        $progress->getPoints()
                    );
                }
            }
        }

        return $this->json([
            'pps' => $pps
        ]);
    }

//    #[Route('/progress', name: 'app_rating_progress')]
//    public function prog()
//    {
//        $userProgress = $this->userPersonalAwardsRepository->findAll();
//        $pps = [];
//        foreach ($userProgress as $progress) {
//            if (isset($pps[$progress->getUser()->getId()])) {
//                /** @var PpsProgressDto $dto */
//                $dto = $pps[$progress->getUser()->getId()];
//                $dto->progressPoints += $progress->getPoints();
//            } else {
//                $pps[$progress->getUser()->getId()] = new PpsProgressDto(
//                    $progress->getUser()->getUsername(),
//                    $progress->getPoints()
//                );
//            }
//        }
//
//        return $this->json([
//            $pps
//        ]);
//    }
}
