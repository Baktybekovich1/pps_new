<?php

namespace App\Tenant;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Attributes newly created tenant rows to the organization of the request.
 *
 * The counterpart of {@see OrganizationFilter}: reads are constrained
 * automatically, so writes have to be attributed automatically too. A row
 * without an organization is archive and invisible to every organization, so
 * a signed-in principal who has not picked one is refused instead.
 */
#[AsDoctrineListener(event: Events::prePersist)]
class OrganizationStamper
{
    public function __construct(
        private readonly OrganizationContext $context,
        private readonly Security            $security,
    )
    {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof OrganizationOwnedInterface || null !== $entity->getOrganization()) {
            return;
        }

        $organization = $this->context->get();
        if (null !== $organization) {
            $entity->setOrganization($organization);

            return;
        }

        if (null !== $this->security->getUser()) {
            throw new ConflictHttpException('Не выбрана организация.');
        }
    }
}
