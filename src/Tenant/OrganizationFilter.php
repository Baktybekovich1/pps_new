<?php

namespace App\Tenant;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Constrains every query on an {@see OrganizationOwnedInterface} entity to the
 * organization of the current request, so isolation does not depend on each
 * repository method remembering to filter.
 *
 * Enabled and parameterised by {@see OrganizationFilterConfigurator}.
 */
class OrganizationFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if (!$targetEntity->getReflectionClass()->implementsInterface(OrganizationOwnedInterface::class)) {
            return '';
        }

        try {
            $organizationId = $this->getParameter('organization_id');
        } catch (\InvalidArgumentException) {
            // Filter enabled but not parameterised yet — constrain nothing
            // rather than produce broken SQL.
            return '';
        }

        // Rows without an organization predate the split into independent
        // organizations. They are kept as an archive and belong to nobody.
        return sprintf('%s.organization_id = %s', $targetTableAlias, $organizationId);
    }
}
