<?php

namespace App\Service\Institute;

use App\Dto\Institute\Question\SetInstituteQuestionSubtitleDto;
use App\Dto\Institute\Question\SetInstituteQuestionTitleDto;
use App\Entity\InstituteQuestionSubtitle;
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
        $questionTitle->setName($dto->titleName);
        $questionTitle->setActive($dto->isActive);
        return $this->instituteQuestionTitleRepository->save($questionTitle);
    }

    public function setInstituteQuestionSubtitle(SetInstituteQuestionSubtitleDto $dto): bool
    {
        $questionSubtitle = new InstituteQuestionSubtitle();
        $questionSubtitle->setName($dto->subtitleName);
        $questionSubtitle->setTitle($this->instituteQuestionTitleRepository->find($dto->titleId));
        $questionSubtitle->setPoint($dto->point);
        return $this->instituteQuestionSubtitleRepository->save($questionSubtitle);
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

    public function removeInstituteQuestionTitle($titleId): bool
    {
        return $this->instituteQuestionTitleRepository->remove($titleId);
    }

    public function removeInstituteQuestionSubtitle($subtitleId): bool
    {
        return $this->instituteQuestionSubtitleRepository->remove($subtitleId);
    }

}