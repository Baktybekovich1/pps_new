<?php

namespace App\Service\Years;

use App\Repository\ResultsOfYearRepository;
use App\Repository\UserRepository;
use App\Repository\YearsRepository;

class RemoveYearService
{
    public function __construct(
        private readonly YearsRepository         $yearsRepository,
        private readonly ResultsOfYearRepository $resultsOfYearRepository
    )
    {
    }

    public function removeYears(int $yearId): bool
    {
        $year = $this->yearsRepository->find($yearId);
        $results = $this->resultsOfYearRepository->findBy(['year' => $year]);
        foreach ($results as $result) {
            $this->resultsOfYearRepository->remove($result);
        }
        $this->yearsRepository->remove($year);
        return true;
    }

}