<?php

namespace App\Service\Instiute;

use App\Entity\InstituteQuestionTitle;
use App\Factory\Institute\InstituteDtoFactory;
use App\Repository\InstituteQuestionTitleRepository;
use App\Repository\InstituteRepository;

class InstituteService
{

    public function __construct(
        private readonly InstituteDtoFactory $dtoFactory,
        private readonly InstituteRepository $instituteRepository,
        private readonly InstituteQuestionTitleRepository $instituteQuestionTitleRepository,
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

    public function getInstituteQuestion(): array
    {
        $questions = $this->instituteQuestionTitleRepository->findAll();
        $result = [];
        foreach ($questions as $question) {
            $result[] = $this->dtoFactory->getName($question);
        }
        return $result;

    }
}