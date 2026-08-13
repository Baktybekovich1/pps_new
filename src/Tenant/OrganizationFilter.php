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

        // TODO: drop the IS NULL branch once the questionnaire and the year
        // calendar are split per organization and the column becomes NOT NULL.
        // Until then rows that predate multi-tenancy are still shared, and
        // hiding them would empty out the questionnaire for everyone.
        return sprintf(
            '(%1$s.organization_id = %2$s OR %1$s.organization_id IS NULL)',
            $targetTableAlias,
            $organizationId
        );
    }
}
