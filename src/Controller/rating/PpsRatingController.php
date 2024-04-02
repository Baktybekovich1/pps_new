<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\UsersDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
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
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }

    #[Route('/pps', name: 'app_pps_rating')]
    public function index(): JsonResponse
    {
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
            $socials = $this->userSocialActivitiesRepository->findBy(['user' => $user]);
            $socialCall = $this->getPoints($socials);
            if (isset($pps[$user->getId()])) {
                /** @var PpsRatingDto $dto */

                $dto = $pps[$user->getId()];
                $dto->id = $user->getId();
                $dto->uralPoints += $activyCall;
                $dto->progressPoints += $upac;
                $dto->educationPoints += $eduCall;
                $dto->socialPoints += $socialCall;
            } else {
                $pps[$user->getId()] = new PpsRatingDto(
                    $user->getId(),
                    $info->getName(),
                    $activyCall,
                    $upac,
                    $eduCall,
                    $socialCall
                );
            }
        }
        return $this->json([
            'pps' => $pps
        ]);
    }

    #[Route('/users', name: 'app_pps_users')]
    public function users_list(): JsonResponse
    {
        $userInfo = $this->userInfoRepository->findAll();
        $users = [];
        foreach ($userInfo as $value) {
            $item = new UsersDto(
                $value->getId(),
                $value->getName()
            );
            $users[] = $item;
        }
        return $this->json([
            'users' => $users
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

}
