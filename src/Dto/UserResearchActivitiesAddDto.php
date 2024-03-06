<?php

namespace App\Dto;

class UserResearchActivitiesAddDto
{
    public function __construct(
        readonly public array $ural,
    )
    {
    }
}