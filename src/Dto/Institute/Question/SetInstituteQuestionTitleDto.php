<?php

namespace App\Dto\Institute\Question;

class SetInstituteQuestionTitleDto
{
    public function __construct(
        public string $name,
        public bool $active,
    )
    {
    }

}