<?php

namespace App\Dto;

class ResearchGetSub
{
    public function __construct(
        public int $int,
        public string $name
    )
    {
    }

}