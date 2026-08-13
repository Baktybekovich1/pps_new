<?php

namespace App\Tenant;

use App\Entity\Organization;
use App\Entity\Teacher;
use App\Repository\DirectorRepository;
use App\Repository\TeacherOrganizationRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Answers "which organization is this request about?".
 *
 * Public rating pages carry the organization in the URL and set it explicitly;
 * everything else derives it from whoever is logged in.
 */
class OrganizationContext
{
    public const ROLE_CROSS_ORGANIZATION = 'ROLE_SUPER_ADMIN';

    private ?Organization $organization = null;
    private bool $resolved = false;

    public function __construct(
        private readonly Security                      $security,
        private readonly DirectorRepository            $directorRepository,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
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
        // request, silently switching isolation off for everything after.
        if (null !== $this->security->getUser()) {
            $this->organization = $organization;
            $this->resolved = true;
        }

        return $organization;
    }

    /**
     * True for principals allowed to look across organizations, who therefore
     * must not be constrained by OrganizationFilter.
     */
    public function isCrossOrganization(): bool
    {
        return $this->security->isGranted(self::ROLE_CROSS_ORGANIZATION);
    }

    private function resolve(): ?Organization
    {
        $user = $this->security->getUser();

        if (!$user instanceof Teacher) {
            return null;
        }

        $director = $this->directorRepository->findOneBy(['teacher' => $user]);
        if (null !== $director) {
            return $director->getInstitute()?->getOrganization();
        }

        if (null !== $user->getExpert()) {
            return $user->getExpert()->getOrganization();
        }

        // A teacher working for several organizations has no single implicit
        // context: which one they are acting for has to be chosen explicitly.
        $memberships = $this->teacherOrganizationRepository->findBy(['teacher' => $user]);
        if (1 !== count($memberships)) {
            return null;
        }

        return $memberships[0]->getOrganization();
    }
}
