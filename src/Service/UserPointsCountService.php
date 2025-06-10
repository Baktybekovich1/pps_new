<?php

namespace App\Service;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\PpsRatingSumDto;
use App\Entity\UserOffence;
use App\Repository\UserExpertPointRepository;
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
        private readonly UserInfoRepository                   $userInfoRepository, private readonly UserExpertPointRepository $userExpertPointRepository)
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
        $activyCall = $this->userActivitiesListsRepository->getUserPoints($user->getId());
        $upac = $this->userPersonalAwardsRepository->getUserPoints($user->getId());
        $eduCall = $this->userInnovativeEducationRepository->getUserPoints($user->getId());
        $socialCall = $this->userSocialActivitiesRepository->getUserPoints($user->getId());
        $offence = $this->userOffenceRepository->getUserPoints($user->getId());
        $sum = $activyCall + $upac + $eduCall + $socialCall - $offence;
        $expertPoints = $this->userExpertPointRepository->findBy(['teacher' => $user]);

        foreach ($expertPoints as $expertPoint) {
            $point = $expertPoint->getPoint();
            $sum += $point;
        }

        return ['research' => $activyCall, 'awards' => $upac, 'innovative' => $eduCall, 'social' => $socialCall, 'sum' => $sum];

    }

}