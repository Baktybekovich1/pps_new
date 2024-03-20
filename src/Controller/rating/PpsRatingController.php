<?php

namespace App\Controller\rating;

use App\Dto\PpsProgressDto;
use App\Dto\PpsRatingDto;
use App\Entity\UserPersonalAwards;
use App\Entity\UserResearchActivitiesList;
use App\Repository\PersonalAwardsRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
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
        private UserRepository                       $userRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository
    )
    {
    }

    #[Route('/pps', name: 'app_pps_rating')]
    public function index(): JsonResponse
    {
//        $pps = $this->userInfoRepository->findOneBy(['userId' => 14]);
//        $activities = $this->userActivitiesListsRepository->findAll();
//        $userProgress = $this->userPersonalAwardsRepository->findAll();
//
//        $pps = [];
//        foreach ($activities as $activity) {
//            foreach ($userProgress as $progress) {
//                if (isset($pps[$activity->getUser()->getId()])) {
//                    /** @var PpsRatingDto $item */
//                    $item = $pps[$activity->getUser()->getId()];
//
//                    $item->uralPoints += $activity->getPoints();
//                    $item->progressPoints += $progress->getPoints();
//
//                } else {
//                    $pps[$activity->getUser()->getId()] = new PpsRatingDto(
//                        $activity->getUser()->getUsername(),
//                        $activity->getPoints(),
//                        $progress->getPoints()
//                    );
//                }
//            }
//        }


        $pps = [];
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $info = $this->userInfoRepository->findOneBy(['userId' => $user->getId()]);
            $activity = $this->userActivitiesListsRepository->findBy(['user' => $user]);
            $activyCall = $this->getPoints($activity);
            $personalAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user]);
            $upac = $this->getPoints($personalAwards);
            $educations = $this->userInnovativeEducationRepository->findBy(['user' => $user]);
            $eduCall = $this->getPoints($educations);
            if (isset($pps[$user->getId()])) {
                /** @var PpsRatingDto $dto */

                $dto = $pps[$user->getId()];
                $dto->id = $user->getId();
                $dto->uralPoints += $activyCall;
                $dto->progressPoints += $upac;
                $dto->educationPoints += $eduCall;
            } else {
                $pps[$user->getId()] = new PpsRatingDto(
                    $user->getId(),
                    $info->getName(),
                    $activyCall,
                    $upac,
                    $eduCall
                );
            }
        }
        return $this->json([
            'pps' => $pps
        ]);
    }


    public function getPoints($objects)
    {
        $coll = 0;
        foreach ($objects as $object) {
            $coll += $object->getPoints();
        }
        return $coll;

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
