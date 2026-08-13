<?php

namespace App\Dto\Organization;

use App\Dto\Institute\InstituteDto;
use App\Entity\Organization;

class OrganizationDto
{
    public function __construct(
        public int    $id,
        public string $name,
        public ?string $photoUrl,
        public array  $institutes
    )
    {
    }


}