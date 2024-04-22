<?php

namespace App\Controller\rating;

use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class InstitutesRatingController extends AbstractController
{
    public function __construct(
        private UserInfoRepository                   $userInfoRepository,
        private UserResearchActivitiesListRepository $userActivitiesListsRepository,
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserRepository                       $userRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository,
        private UserOffenceRepository                $userOffenceRepository
    )
    {
    }

    #[Route('/institutes/rating', name: 'app_institutes_rating')]
    public function index(): JsonResponse
    {

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/InstitutesRatingController.php',
        ]);
    }
}
