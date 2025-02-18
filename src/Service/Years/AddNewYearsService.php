<?php

namespace App\Service\Years;

use App\Entity\Years;
//use App\Repository\ResultsOfYearRepository;
use App\Repository\UserRepository;
use App\Repository\YearsRepository;

readonly class AddNewYearsService
{
    public function __construct(
        private YearsRepository $yearsRepository,
//        private ResultsOfYearRepository $resultsOfYearRepository,
        private UserRepository  $userRepository
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
        return true;
    }

}