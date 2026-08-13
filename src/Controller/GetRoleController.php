<?php

namespace App\Controller;

use App\Dto\UserInfoGetDto;
use App\Entity\Teacher;
use App\Repository\InstituteRepository;
use App\Repository\PositionRepository;
use App\Tenant\OrganizationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class GetRoleController extends AbstractController
{
    public function __construct(
        private readonly InstituteRepository $instituteRepository,
        private readonly PositionRepository  $positionsRepository,
        private readonly OrganizationContext $organizationContext,
    )
    {
    }

//    #[Route('/api/get/role', name: 'app_get_role', methods: ['GET'])]
//    public function index(UserInterface $userStorage): JsonResponse
//    {
//        if ($userStorage->getUserIdentifier() != null) {
//            $user = $this->userRepository->find($userStorage->getUserIdentifier());
//            $role = $user->getRoles();
//            if (count($role) > 1) {
//                $get = 'admin';
//            } else {
//                $get = 'user';
//            }
//            return $this->json([
//                'role' => $get
//            ]);
//        } else {
//            return $this->json([
//                'role' => 'visitor'
//            ]);
//        }
//    }
    #[Route('/api/get/role', name: 'app_get_role', methods: ['GET'])]
    public function index(#[CurrentUser] ?Teacher $teacher): JsonResponse
    {
        if (!$teacher) {
            return $this->json(['role' => 'visitor']);
        }

        // у Teacher роль всегда 1 элемент (ROLE_TEACHER)
        // если нужно «admin» — добавьте поле/флаг
        $roles = $teacher->getRoles();

        if (in_array("ROLE_ADMIN", $roles)) {
            $role = "admin";
        } else if (in_array("ROLE_DIRECTOR", $roles)) {
            $role = "director";
        } else if (in_array("ROLE_EXPERT", $roles)) {
            $role = "expert";
        } else if (in_array("ROLE_TEACHER", $roles)) {
            $role = "teacher";
        } else {
            $role = "visitor";
        }

        // The organization the caller acts for, so the UI can stop offering
        // what OrganizationVoter would refuse. It is set whenever the account
        // has an unambiguous membership, including for a super admin, whose
        // reach is described by isSuperAdmin rather than by this field. Null
        // means the membership itself is ambiguous — several organizations, or
        // none.
        $organization = $this->organizationContext->get();

        return $this->json([
            'role' => $role,
            'isSuperAdmin' => $this->organizationContext->isCrossOrganization(),
            'organization' => null === $organization ? null : [
                'id' => $organization->getId(),
                'name' => $organization->getName(),
            ],
        ]);
    }




    function delete($awards, $repository)
    {
        foreach ($awards as $award) {
            $repository->remove($award);
        }
    }
}
