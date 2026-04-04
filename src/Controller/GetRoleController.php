<?php

namespace App\Controller;

use App\Dto\UserInfoGetDto;
use App\Entity\Teacher;
use App\Repository\InstitutionsRepository;
use App\Repository\PositionRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class GetRoleController extends AbstractController
{
    public function __construct(
        private readonly UserRepository                       $userRepository,
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly InstitutionsRepository               $institutionsRepository,
        private readonly PositionRepository                   $positionsRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly UserPasswordHasherInterface          $passwordHasher
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

        return $this->json(['role' => $role]);
    }


    #[Route('api/user/info', name: 'app_user_info', methods: ['GET'])]
    public function user_info(): JsonResponse
    {
        $positions = $this->positionsRepository->findAll();
        $institutes = $this->institutionsRepository->findAll();
        $university = ["МУИТ", "КИТЭ", "Комтехно"];
        $post = [];
//        foreach ($institutes as $institute) {
//            $inst[] = $institute->getName();
//        }
        foreach ($positions as $position) {
            $post[] = $position->getName();
        }

        return $this->json([
            'university' => $university,
            'institutes' => $institutes,
            'position' => $post
        ]);
    }

    #[Route(path: '/change/pro/pass', name: 'change_pro_pass', methods: ['GET'])]
    public function change_pro_pass(): JsonResponse
    {
        $user = $this->userRepository->find(66);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'marsel2013'));
        $this->userRepository->save($user);
        return $this->json('Success');
    }

    function delete($awards, $repository)
    {
        foreach ($awards as $award) {
            $repository->remove($award);
        }
    }
}
