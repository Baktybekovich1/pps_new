<?php

namespace App\Dto\Institute\Question;

class SetInstituteQuestionSubtitleDto
{
    public function __construct(
        public int $titleId,
        public string $name,
        public int $point,
    )
    {
    }

}