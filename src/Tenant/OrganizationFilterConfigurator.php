<?php

namespace App\Tenant;

use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Turns OrganizationFilter on for the organization the request is about.
 *
 * Hooked on kernel.controller: by the time a controller is picked the firewall
 * and the access checks have both run, so the principal is settled. Argument
 * resolution, including MapEntity lookups, happens after this, so entities
 * fetched for the controller are already constrained.
 */
#[AsEventListener(event: KernelEvents::CONTROLLER)]
class OrganizationFilterConfigurator
{
    public const FILTER_NAME = 'organization';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrganizationContext    $context,
        private readonly OrganizationRepository $organizationRepository,
    )
    {
    }

    public function __invoke(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        // Public rating routes are about an organization named in the URL, not
        // about whoever happens to be logged in.
        $orgId = $event->getRequest()->attributes->get('orgId');
        if (null !== $orgId) {
            $this->context->set($this->organizationRepository->find($orgId));
        }

        $filters = $this->entityManager->getFilters();
        $organization = $this->context->get();

        if (null === $organization || $this->context->isCrossOrganization()) {
            if ($filters->isEnabled(self::FILTER_NAME)) {
                $filters->disable(self::FILTER_NAME);
            }

            return;
        }

        $filter = $filters->isEnabled(self::FILTER_NAME)
            ? $filters->getFilter(self::FILTER_NAME)
            : $filters->enable(self::FILTER_NAME);

        $filter->setParameter('organization_id', $organization->getId());
    }
}
