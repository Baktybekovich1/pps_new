<?php

namespace App\Dto\Institute\Question;

class SetInstituteQuestionSubtitleDto
{
    public function __construct(
        public int $titleId,
        public string $subtitleName,
        public int $point,
    )
    {
    }

}