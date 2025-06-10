<?php

namespace App\Service\Years;

use App\Entity\ResultsOfYear;
use App\Entity\Years;

use App\Repository\ResultsOfYearRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use App\Repository\YearsRepository;
use App\Service\GetUserAllPointsSumService;

readonly class AddNewYearsService
{
    public function __construct(
        private YearsRepository            $yearsRepository,
        private ResultsOfYearRepository    $resultsOfYearRepository,
        private UserRepository             $userRepository,
        private GetUserAllPointsSumService $getUserAllPointsSumService, private UserPersonalAwardsRepository $userPersonalAwardsRepository, private UserResearchActivitiesListRepository $userResearchActivitiesListRepository, private UserInnovativeEducationRepository $userInnovativeEducationRepository, private UserSocialActivitiesRepository $userSocialActivitiesRepository
    )
    {
    }

    public function addYear(string $name): bool
    {
        $year = new Years();
        $year->setName($name);
        $this->yearsRepository->save($year);
        $year = $this->yearsRepository->findOneBy(['name' => $name]);
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $results = new ResultsOfYear();
            $results->setYear($year);
            $results->setAccount($user);
            $results->setAwardPoints($this->userPersonalAwardsRepository->getUserPoints($user->getId()));
            $results->setResearchPoints($this->userResearchActivitiesListRepository->getUserPoints($user->getId()));
            $results->setInnovativePoints($this->userInnovativeEducationRepository->getUserPoints($user->getId()));
            $results->setSocialPoints($this->userSocialActivitiesRepository->getUserPoints($user->getId()));
            $results->setSumPoints($this->getUserAllPointsSumService->getUserAllPointsSum($user->getId()));
            $this->resultsOfYearRepository->save($results);
        }
        return true;

    }

}