<?php

namespace App\Controller;

use App\Dto\UserInfoGetDto;
use App\Repository\InstitutionsRepository;
use App\Repository\PositionRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GetRoleController extends AbstractController
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly UserInfoRepository     $userInfoRepository,
        private readonly InstitutionsRepository $institutionsRepository,
        private readonly PositionRepository     $positionsRepository
    )
    {
    }

    #[Route('/api/get/role', name: 'app_get_role')]
    public function index(UserInterface $userStorage): JsonResponse
    {
        $user = $this->userRepository->find($userStorage->getUserIdentifier());
        $role = $user->getRoles();
        if (count($role) > 1) {
            $get = 'admin';
        } else {
            $get = 'user';
        }
        return $this->json([
            'role' => $get
        ]);
    }

    #[Route('api/user/name', name: 'app_user_name')]
    public function user_name(UserInterface $user): JsonResponse
    {
        $user = $this->userRepository->find($user->getUserIdentifier());
        if ($this->userInfoRepository->findOneBy(['user' => $user]) == null) {
            return $this->json([null]);
        } else {
            $userInfo = $this->userInfoRepository->findOneBy(['user' => $user]);
            $dto = new UserInfoGetDto(
                $userInfo->getId(),
                $userInfo->getName(),
                $userInfo->getInstitutions()->getName(),
                $userInfo->getPosition()->getName(),
                $userInfo->getRegular(),
                $userInfo->getEmail()
            );
        }


        return $this->json([
            'user' => $dto
        ]);
    }

    #[Route('api/user/info', name: 'app_user_info')]
    public function user_info(): JsonResponse
    {
        $positions = $this->positionsRepository->findAll();
        $institutes = $this->institutionsRepository->findAll();
        $inst = [];
        $post = [];
        foreach ($institutes as $institute) {
            $inst[] = $institute->getName();
        }
        foreach ($positions as $position) {
            $post[] = $position->getName();
        }
        return $this->json([
            'institutes' => $inst,
            'position' => $post
        ]);
    }

}
