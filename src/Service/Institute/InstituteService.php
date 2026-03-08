<?php

namespace App\Service\Institute;

use App\Dto\Institute\InstituteDto;
use App\Dto\Institute\Question\SetInstituteQuestionTitleDto;
use App\Entity\InstituteQuestionTitle;
use App\Factory\Institute\InstituteDtoFactory;
use App\Repository\InstituteQuestionTitleRepository;
use App\Repository\InstituteRepository;

class InstituteService
{

    public function __construct(
        private readonly InstituteDtoFactory $dtoFactory,
        private readonly InstituteRepository $instituteRepository,
    )
    {
    }

    public function getAllInstitutes(): array
    {
        $institutes = $this->instituteRepository->findAll();
        $result = [];
        foreach ($institutes as $institute) {
            $result[] = $this->dtoFactory->getName($institute);
        }
        return $result;
    }

}