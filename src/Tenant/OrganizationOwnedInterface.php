<?php

namespace App\Tenant;

use App\Entity\Organization;

/**
 * Marks an entity whose rows belong to a single organization (tenant).
 *
 * OrganizationFilter relies on this interface to decide which tables it has to
 * constrain, so an entity that carries tenant data must implement it — adding
 * an organization column without it leaves the table unfiltered.
 */
interface OrganizationOwnedInterface
{
    public function getOrganization(): ?Organization;

    public function setOrganization(?Organization $organization): static;
}
