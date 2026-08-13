<?php

namespace App\Security;

use App\Entity\Organization;
use App\Tenant\OrganizationContext;
use App\Tenant\OrganizationOwnedInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Guards data that belongs to one organization against principals of another.
 *
 * OrganizationFilter already hides such rows from queries; this is the check
 * for the cases the filter cannot cover — anything reached by an explicit id,
 * where a wrong id would otherwise be acted upon.
 */
class OrganizationVoter extends Voter
{
    public const ACCESS = 'ORGANIZATION_ACCESS';

    public function __construct(private readonly OrganizationContext $context)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ACCESS === $attribute
            && ($subject instanceof Organization || $subject instanceof OrganizationOwnedInterface);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($this->context->isCrossOrganization()) {
            return true;
        }

        $current = $this->context->get();
        if (null === $current) {
            return false;
        }

        $target = $subject instanceof Organization ? $subject : $subject->getOrganization();

        // Rows that predate multi-tenancy are not assigned yet and stay
        // readable; see the matching note in OrganizationFilter.
        if (null === $target) {
            return true;
        }

        return $target->getId() === $current->getId();
    }
}
