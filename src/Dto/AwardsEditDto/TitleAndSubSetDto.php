<?php

namespace App\Dto\AwardsEditDto;

class TitleAndSubSetDto
{
    public function __construct(
        public int $titleId,
        public string $titleName,
        public array $subtitles
    )
    {
    }

}