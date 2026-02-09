<?php

namespace App\Service\Organization;

use App\Factory\Organization\OrganizationDtoFactory;
use App\Repository\OrganizationRepository;

class OrganizationService
{
    public function __construct(
        private readonly OrganizationRepository $organizationRepository,
        private readonly OrganizationDtoFactory $organizationDtoFactory
    )
    {
    }

    public function getOrganization(int $organizationId)
    {
        $organizationEntity = $this->organizationRepository->find($organizationId);

        return $this->organizationDtoFactory->fromEntity($organizationEntity);
    }

}