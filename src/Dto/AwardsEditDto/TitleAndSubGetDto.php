<?php

namespace App\Dto\AwardsEditDto;

class TitleAndSubGetDto
{
    public function __construct(
        public int $titleId,
        public string $titleName,
        public array $subtitle
    )
    {
    }

}