<?php

namespace App\Tenant;

use App\Entity\Organization;
use App\Entity\Teacher;
use App\Repository\DirectorRepository;
use App\Repository\OrganizationRepository;
use App\Repository\TeacherOrganizationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Answers "which organization is this request about?".
 *
 * Public rating pages carry the organization in the URL and set it explicitly.
 * A signed-in account names it in the X-Organization-Id header, which is
 * checked against the organizations the account belongs to; without the
 * header an account that belongs to exactly one organization acts for it.
 */
class OrganizationContext
{
    public const ROLE_CROSS_ORGANIZATION = 'ROLE_SUPER_ADMIN';
    public const HEADER = 'X-Organization-Id';

    private ?Organization $organization = null;
    private bool $resolved = false;

    public function __construct(
        private readonly Security                      $security,
        private readonly RequestStack                  $requestStack,
        private readonly DirectorRepository            $directorRepository,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
        private readonly OrganizationRepository        $organizationRepository,
    )
    {
    }

    /**
     * Pins the context, e.g. from an {orgId} route parameter. Access still has
     * to be authorised separately — see OrganizationVoter.
     */
    public function set(?Organization $organization): void
    {
        $this->organization = $organization;
        $this->resolved = true;
    }

    public function get(): ?Organization
    {
        if ($this->resolved) {
            return $this->organization;
        }

        $organization = $this->resolve();

        // The answer is only worth remembering once there is someone to answer
        // for. Something may ask before authentication has run — a console
        // command, or a flush during boot reaching OrganizationStamper — and
        // caching that "no organization" would freeze it for the rest of the
        // request.
        if (null !== $this->security->getUser()) {
            $this->organization = $organization;
            $this->resolved = true;
        }

        return $organization;
    }

    /**
     * True for principals allowed to look across organizations, who therefore
     * must not be constrained by OrganizationFilter unless they pick one.
     */
    public function isCrossOrganization(): bool
    {
        return $this->security->isGranted(self::ROLE_CROSS_ORGANIZATION);
    }

    /**
     * Organizations the signed-in account may act for, keyed by id.
     *
     * @return array<int, Organization>
     */
    public function available(): array
    {
        $user = $this->security->getUser();
        if (!$user instanceof Teacher) {
            return [];
        }

        if ($this->isCrossOrganization()) {
            $all = [];
            foreach ($this->organizationRepository->findAll() as $organization) {
                $all[$organization->getId()] = $organization;
            }

            return $all;
        }

        $organizations = [];
        foreach ($this->teacherOrganizationRepository->findBy(['teacher' => $user]) as $membership) {
            $organizations[$membership->getOrganization()->getId()] = $membership->getOrganization();
        }

        $director = $this->directorRepository->findOneBy(['teacher' => $user]);
        $directorOrganization = $director?->getInstitute()?->getOrganization();
        if (null !== $directorOrganization) {
            $organizations[$directorOrganization->getId()] = $directorOrganization;
        }

        $expertOrganization = $user->getExpert()?->getOrganization();
        if (null !== $expertOrganization) {
            $organizations[$expertOrganization->getId()] = $expertOrganization;
        }

        return $organizations;
    }

    private function resolve(): ?Organization
    {
        if (!$this->security->getUser() instanceof Teacher) {
            return null;
        }

        $requestedId = $this->requestStack->getMainRequest()?->headers->get(self::HEADER);
        $available = $this->available();

        if (null !== $requestedId && '' !== $requestedId) {
            $organization = $available[(int) $requestedId] ?? null;
            if (null === $organization) {
                throw new AccessDeniedHttpException('Нет доступа к выбранной организации.');
            }

            return $organization;
        }

        // A super admin reaches every organization, so belonging to one says
        // nothing about which they mean.
        if ($this->isCrossOrganization()) {
            return null;
        }

        return 1 === count($available) ? reset($available) : null;
    }
}
