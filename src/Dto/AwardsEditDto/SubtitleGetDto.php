<?php

namespace App\Dto\AwardsEditDto;

class SubtitleGetDto
{
    public function __construct(
        public int $subId,
        public string $subName
    )
    {
    }

}