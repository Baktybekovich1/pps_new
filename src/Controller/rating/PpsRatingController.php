<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\UsersDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use App\Service\UserPointsCountService;
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
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository,
        private UserOffenceRepository                $userOffenceRepository,
        private UserPointsCountService $userPointsCountService
    )
    {
    }

    #[Route('/pps', name: 'app_pps_rating')]
    public function index(): JsonResponse
    {
        $pps = $this->userPointsCountService->UserPointsCount();

        return $this->json(['pps' => $pps]);

    }







    #[Route('/users', name: 'app_pps_users')]
    public function users_list(): JsonResponse
    {
        $userInfo = $this->userInfoRepository->findAll();
        $users = [];
        foreach ($userInfo as $value) {
            $item = new UsersDto(
                $value->getUser()->getId(),
                $value->getName()
            );
            $users[] = $item;
        }
        return $this->json([
            'users' => $users
        ]);
    }




}
