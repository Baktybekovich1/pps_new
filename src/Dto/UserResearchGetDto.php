<?php

namespace App\Dto;

use Doctrine\ORM\Mapping\Entity;

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