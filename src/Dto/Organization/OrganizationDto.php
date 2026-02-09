<?php

namespace App\Dto\Organization;

use App\Dto\Institute\InstituteDto;
use App\Entity\Organization;

class OrganizationDto
{
    public function __construct(
        public int $id,
        public string $name,
        public array $institutes
    )
    {
    }

    public function construct(Organization $organization)
    {

        $this->id = $organization->getId();
        $this->name = $organization->getName();
        $institutes = $organization->getInstitutes();
        foreach ($institutes as $institute) {
            $this->institutes[] = new InstituteDto($institute);
        }
    }

}