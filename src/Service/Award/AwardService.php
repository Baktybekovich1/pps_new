<?php

namespace App\Service\Award;

use App\Dto\Award\GetAwardDto;
use App\Factory\Award\GetAwardDtoFactory;
use App\Repository\QuestionTitleRepository;

readonly class AwardService
{
    public function __construct(
        private GetAwardDtoFactory $getAwardDtoFactory,
    )
    {
    }

    public function getAll(): GetAwardDto
    {
        return $this->getAwardDtoFactory->factory();
    }
}