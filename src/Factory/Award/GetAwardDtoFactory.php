<?php

namespace App\Factory\Award;

use App\Dto\Award\GetAwardDto;
use App\Dto\Award\GetStageDto;
use App\Entity\Stage;
use App\Repository\QuestionTitleRepository;
use App\Repository\StageRepository;

readonly class GetAwardDtoFactory
{

    public function __construct(
        private StageRepository $stageRepository,
        private readonly GetStageDtoFactory $getStageDtoFactory,
    )
    {
    }

    public function factory(): GetAwardDto
    {
        $stages = $this->stageRepository->findBy(['active' => true]);
        $award = new GetAwardDto();
        foreach ($stages as $stage) {
            $award->stages[] = $this->getStageDtoFactory->factory($stage);
        }
        return $award;


    }
}