<?php

namespace App\Dto;

class UserResearchActivitiesAddDto
{
    public function __construct(
        readonly public string $name,
        readonly public array $ural
    )
    {
    }
}