<?php

namespace App\Dto\UserStagesGet;

class UserResearchGetDto
{
    public function __construct(
        public int    $id,
        public string $name,
        public array  $researchActivitiesSubtitles
    )
    {
    }

}