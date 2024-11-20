<?php

namespace App\Service;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\PpsRatingSumDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;

class UserPointsCountSumService
{
    public function __construct(
        private readonly UserResearchActivitiesListRepository $userActivitiesListsRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly UserRepository                       $userRepository,
        private readonly UserInfoRepository                   $userInfoRepository)
    {
    }

    public function UserPointsCount(): array
    {
        $pps = [];
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $info = $this->userInfoRepository->findOneBy(['user' => $user]);

            if ($info == null) {

                continue;

            }
            if ($info->getInstitutions()->getUniversity() != 'МУИТ') {
                continue;
            }

            $fun = $this->getBigPoints($user);


            if (isset($pps[$user->getId()])) {
                /** @var PpsRatingSumDto $dto */

                $dto = $pps[$user->getId()];
                $dto->user = $user;
                $dto->points = $fun['sum'];


            } else {
                $pps[$user->getId()] = new PpsRatingSumDto(
                    $user,
                    $fun['sum'],
                );
            }
        }
        return $pps;

    }

    public function getBigPoints($user)
    {
        $activity = $this->userActivitiesListsRepository->findBy(['user' => $user, 'status' => 'active']);
        $activyCall = $this->getPoints($activity);
        $personalAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user, 'status' => 'active']);
        $upac = $this->getPoints($personalAwards);
        $educations = $this->userInnovativeEducationRepository->findBy(['user' => $user, 'status' => 'active']);
        $eduCall = $this->getPoints($educations);
        $socials = $this->userSocialActivitiesRepository->findBy(['user' => $user, 'status' => 'active']);
        $socialCall = $this->getPoints($socials);
        $offence = $this->userOffenceRepository->findBy(['user' => $user]);
        $sum = $activyCall + $upac + $eduCall + $socialCall;
        foreach ($offence as $value) {
            $sum -= $value->getOffenceList()->getPoints() * $value->getQuantity();
        }

        return ['sum' => $sum];

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