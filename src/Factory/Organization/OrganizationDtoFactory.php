<?php

namespace App\Factory\Organization;

use App\Dto\Organization\OrganizationDto;
use App\Entity\Organization;
use App\Factory\Institute\InstituteDtoFactory;

class OrganizationDtoFactory
{

    public function __construct(
        private readonly InstituteDtoFactory $instituteDtoFactory,
    )
    {
    }

    public function fromEntity(Organization $organization): OrganizationDto
    {
        $institutes = [];
        foreach ($organization->getInstitutes() as $institute) {
            $institutes[] = $this->instituteDtoFactory->fromEntity($institute);
        }
        return new OrganizationDto(
            $organization->getId(),
            $organization->getName(),
            $organization->getPhotoUrl(),
            $institutes
        );
    }

}