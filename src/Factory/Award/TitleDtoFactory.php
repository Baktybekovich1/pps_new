<?php

namespace App\Factory\Award;

use App\Dto\Award\TitleDto;
use App\Entity\QuestionTitle;
use App\Repository\QuestionSubtitleRepository;
use App\Repository\QuestionTitleRepository;

class TitleDtoFactory
{
    public function __construct(
        private readonly QuestionSubtitleRepository $subtitleRepository,
    private readonly SubtitleDtoFactory $subtitleDtoFactory,)
    {
    }

    public function factory(QuestionTitle $questionTitle): TitleDto
    {
        $subtitles = $this->subtitleRepository->findBy(['title' => $questionTitle]);
        $titleDto = new TitleDto();
        $titleDto->titleId = $questionTitle->getId();
        $titleDto->titleName = $questionTitle->getName();
        foreach ($subtitles as $subtitle) {
            $titleDto->subtitles[] = $this->subtitleDtoFactory->factory($subtitle);
        }
        return $titleDto;

    }

}