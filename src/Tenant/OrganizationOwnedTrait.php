<?php

namespace App\Tenant;

use App\Entity\Organization;
use Doctrine\ORM\Mapping as ORM;

/**
 * Default implementation of {@see OrganizationOwnedInterface}.
 *
 * The column stays nullable for now: existing rows predate multi-tenancy and
 * are backfilled only where the organization is derivable. It becomes NOT NULL
 * once the questionnaire is split per organization.
 *
 * onDelete: CASCADE matches how institute and teacher_organization already
 * behave — removing an organization removes its data. Deliberately not
 * SET NULL: a null organization means "not assigned yet", and silently
 * producing such rows would let them leak across tenants.
 */
trait OrganizationOwnedTrait
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Organization $organization = null;

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }
}
