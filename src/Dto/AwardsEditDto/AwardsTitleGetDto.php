<?php

namespace App\Dto\AwardsEditDto;

class AwardsTitleGetDto
{
    public function __construct(
        public int    $id,
        public string $name
    )
    {
    }
}