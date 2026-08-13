<?php

namespace App\Tenant;

use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Turns OrganizationFilter on for the organization the request is about.
 *
 * Runs after the firewall (priority below 8) so the authenticated principal is
 * already available.
 */
#[AsEventListener(event: KernelEvents::REQUEST, priority: 4)]
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

    public function __invoke(RequestEvent $event): void
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
