<?php

namespace App\Service;

use App\Dto\RatingDto\PpsRatingSumDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;

class GetUserAllPointsSumService
{
    public function __construct(
        private readonly UserResearchActivitiesListRepository $userActivitiesListsRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly UserRepository                       $userRepository,
        private readonly UserInfoRepository                   $userInfoRepository, private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository)
    {
    }

    public function getUserAllPointsSum(int $userId): int
    {
        $user = $this->userRepository->find($userId);

        $sum = $this->userPersonalAwardsRepository->getUserPoints($userId)
            + $this->userInnovativeEducationRepository->getUserPoints($userId)
            + $this->userSocialActivitiesRepository->getUserPoints($userId)
            + $this->userResearchActivitiesListRepository->getUserPoints($userId);

        $offence = $this->userOffenceRepository->getUserPoints($userId);


        return $sum - $offence;
    }

}