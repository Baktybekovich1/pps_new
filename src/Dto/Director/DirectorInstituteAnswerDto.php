<?php

namespace App\Dto\Director;

class DirectorInstituteAnswerDto
{
    public function __construct(
        public int $id,
        public ?string $titleName,
        public ?string $subtitleName,
        public ?string $answerLink,
        public ?int $point
    ) {}
}
