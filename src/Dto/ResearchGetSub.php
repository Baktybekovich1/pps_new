<?php

namespace App\Dto;

class ResearchGetSub
{
    public function __construct(
        public int $id,
        public string $name,

    )
    {
    }

}