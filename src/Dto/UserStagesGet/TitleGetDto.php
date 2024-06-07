<?php

namespace App\Dto\UserStagesGet;

class TitleGetDto
{
    public function __construct(
        public int $id,
        public string $name,
    )
    {
    }

}