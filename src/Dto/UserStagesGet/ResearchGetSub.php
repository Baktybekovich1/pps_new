<?php

namespace App\Dto\UserStagesGet;

class ResearchGetSub
{
    public function __construct(
        public int $id,
        public string $name
    )
    {
    }

}