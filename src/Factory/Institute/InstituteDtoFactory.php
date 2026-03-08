<?php

namespace App\Factory\Institute;

use App\Dto\Institute\InstituteDto;
use App\Dto\Institute\InstituteNameDto;
use App\Dto\Institute\Question\GetInstituteQuestionSubtitleDto;
use App\Dto\Institute\Question\GetInstituteQuestionTitleDto;
use App\Entity\Institute;
use App\Entity\InstituteQuestionSubtitle;
use App\Entity\InstituteQuestionTitle;

class InstituteDtoFactory
{
    public function fromEntity(Institute $institute): InstituteDto
    {
        return new InstituteDto(
            $institute->getId(),
            $institute->getName(),
            $institute->getTeacherTotal(),
        );
    }

    public function getName(Institute $institute): InstituteNameDto
    {
        $dto = new InstituteNameDto();
        $dto->instituteId = $institute->getId();
        $dto->name = (string)$institute;
        return $dto;

    }

    public function getQuestionTitle(InstituteQuestionTitle $title): GetInstituteQuestionTitleDto
    {
        $dto = new GetInstituteQuestionTitleDto();
        $dto->titleId = $title->getId();
        $dto->titleName = $title->getName();
        $dto->isActive = $title->isActive();
        return $dto;
    }

    public function getQuestionSubtitle(InstituteQuestionSubtitle $subtitle): GetInstituteQuestionSubtitleDto
    {
        $dto = new GetInstituteQuestionSubtitleDto();
        $dto->subtitleId = $subtitle->getId();
        $dto->subtitleName = $subtitle->getName();
        $dto->titleId = $subtitle->getTitle()->getId();
        $dto->point = $subtitle->getPoint();
        return $dto;
    }


}