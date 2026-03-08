<?php

namespace App\Dto\Institute\Question;

class SetInstituteQuestionTitleDto
{
    public function __construct(
        public string $titleName,
        public bool $isActive,
    )
    {
    }

}