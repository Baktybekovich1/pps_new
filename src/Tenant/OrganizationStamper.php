<?php

namespace App\Tenant;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

/**
 * Attributes newly created tenant rows to the organization of the request.
 *
 * The counterpart of {@see OrganizationFilter}: reads are constrained
 * automatically, so writes have to be attributed automatically too. Without
 * this a row lands with a null organization, and a null organization is
 * readable by everyone — an admin adding a rating stage would have added it to
 * every organization at once.
 */
#[AsDoctrineListener(event: Events::prePersist)]
class OrganizationStamper
{
    public function __construct(private readonly OrganizationContext $context)
    {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof OrganizationOwnedInterface || null !== $entity->getOrganization()) {
            return;
        }

        // Someone who works across organizations has not said which one they
        // are authoring for, so the row stays shared rather than being guessed
        // into their own. Ordinary admins have exactly one answer.
        if ($this->context->isCrossOrganization()) {
            return;
        }

        $organization = $this->context->get();
        if (null === $organization) {
            return;
        }

        $entity->setOrganization($organization);
    }
}
