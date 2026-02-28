<?php

namespace App\Factory\Award;

use App\Dto\Award\GetStageDto;
use App\Entity\Stage;
use App\Repository\QuestionTitleRepository;

readonly class GetStageDtoFactory
{
    public function __construct(
        private QuestionTitleRepository $questionTitleRepository,
        private TitleDtoFactory         $titleDtoFactory,
    )
    {
    }

    public function factory(Stage $stage): GetStageDto
    {
        $titles = $this->questionTitleRepository->findBy(['stage' => $stage]);
        $award = new GetStageDto();
        $award->stageId = $stage->getId();
        $award->stageName = $stage->getName();
        foreach ($titles as $title) {
            $award->titles[] = $this->titleDtoFactory->factory($title);
        }
        return $award;
    }

}