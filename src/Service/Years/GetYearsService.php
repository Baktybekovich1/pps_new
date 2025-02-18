<?php

namespace App\Service\Years;

use App\Dto\Years\GetYearsDto;
use App\Dto\Years\GetYearUserPointsDto;
use App\Repository\ResultsOfYearRepository;
use App\Repository\UserRepository;
use App\Repository\YearsRepository;
use Doctrine\DBAL\Exception;

class GetYearsService
{
    public function __construct(
        private readonly YearsRepository         $yearsRepository,
        private readonly UserRepository          $userRepository,
        private readonly ResultsOfYearRepository $resultsOfYearRepository
    )
    {
    }

    public function getYears(): array
    {
        $years = [];
        $db_years = $this->yearsRepository->findAll();
        $users = $this->userRepository->findAll();
        foreach ($db_years as $db_year) {
            $teachers = [];
            foreach ($users as $user) {
                if ($this->resultsOfYearRepository->findOneBy(['year' => $db_year, 'account' => $user]) != null) {
                    $teachers[] = new GetYearUserPointsDto(
                        $user->getId(),
                        $user->getUsername(),
                        $this->resultsOfYearRepository->findOneBy(['year' => $db_year, 'account' => $user])->getPoints()
                    );
                }
            }
            $years[] = new GetYearsDto(
                $db_year->getId(),
                $db_year->getName(),
                $teachers
            );
        }
        return $years;
    }

}