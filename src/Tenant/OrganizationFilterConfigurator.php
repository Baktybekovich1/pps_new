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
    private const NO_ORGANIZATION = 0;

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

        $this->apply();
    }

    /**
     * Runs $callback with tenant isolation lifted, then restores it.
     *
     * For the few lookups that legitimately span organizations, such as a
     * teacher choosing workplaces in organizations they do not belong to yet.
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function withoutIsolation(callable $callback): mixed
    {
        $filters = $this->entityManager->getFilters();
        if (!$filters->isEnabled(self::FILTER_NAME)) {
            return $callback();
        }

        $filters->disable(self::FILTER_NAME);
        try {
            return $callback();
        } finally {
            $this->apply();
        }
    }

    private function apply(): void
    {
        $filters = $this->entityManager->getFilters();
        $organization = $this->context->get();

        // Only a super admin who has not picked an organization looks across
        // all of them. Anyone else without an organization sees no tenant
        // data at all, rather than everyone's.
        if (null === $organization && $this->context->isCrossOrganization()) {
            if ($filters->isEnabled(self::FILTER_NAME)) {
                $filters->disable(self::FILTER_NAME);
            }

            return;
        }

        $filter = $filters->isEnabled(self::FILTER_NAME)
            ? $filters->getFilter(self::FILTER_NAME)
            : $filters->enable(self::FILTER_NAME);

        $filter->setParameter('organization_id', $organization?->getId() ?? self::NO_ORGANIZATION);
    }
}
