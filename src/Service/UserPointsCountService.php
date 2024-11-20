<?php

namespace App\Service;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\PpsRatingSumDto;
use App\Entity\UserOffence;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;

class UserPointsCountService
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
                /** @var PpsRatingDto $dto */

                $dto = $pps[$user->getId()];
                $dto->id = $user->getId();
                $dto->researchPoints += $fun['research'];
                $dto->awardPoints += $fun['awards'];
                $dto->innovativePoints += $fun['innovative'];
                $dto->socialPoints += $fun['social'];
                $dto->sum += $fun['sum'];

            } else {
                $pps[$user->getId()] = new PpsRatingDto(
                    $user->getId(),
                    $info->getName(),
                    $info->getInstitutions()->getName(),
                    $fun['research'],
                    $fun['awards'],
                    $fun['innovative'],
                    $fun['social'],
                    $fun['sum']
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

        return ['research' => $activyCall, 'awards' => $upac, 'innovative' => $eduCall, 'social' => $socialCall, 'sum' => $sum];

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