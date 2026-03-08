<?php

namespace App\Service\Institute;

use App\Dto\Institute\Question\SetInstituteQuestionTitleDto;
use App\Entity\InstituteQuestionTitle;
use App\Factory\Institute\InstituteDtoFactory;
use App\Repository\InstituteQuestionSubtitleRepository;
use App\Repository\InstituteQuestionTitleRepository;

class InstituteQuestionService
{
    public function __construct(
        private readonly InstituteQuestionTitleRepository    $instituteQuestionTitleRepository,
        private readonly InstituteQuestionSubtitleRepository $instituteQuestionSubtitleRepository,
        private readonly InstituteDtoFactory                 $dtoFactory,
    )
    {
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

    public function setInstituteQuestionTitle(SetInstituteQuestionTitleDto $dto): bool
    {
        $questionTitle = new InstituteQuestionTitle();
        $questionTitle->setName($dto->name);
        $questionTitle->setActive($dto->active);
        return $this->instituteQuestionTitleRepository->save($questionTitle);
    }

    public function getTitles(): array
    {
        $questions = $this->instituteQuestionTitleRepository->findAll();
        $result = [];
        foreach ($questions as $question) {
            $result[] = $this->dtoFactory->getQuestionTitle($question);
        }
        return $result;
    }

    public function getSubtitles(): array
    {
        $subtitles = $this->instituteQuestionSubtitleRepository->findAll();
        $result = [];
        foreach ($subtitles as $subtitle) {
            $result[] = $this->dtoFactory->getQuestionSubtitle($subtitle);
        }
        return $result;
    }
}