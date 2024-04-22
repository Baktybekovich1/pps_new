<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GetRoleController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository
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
        }
        else {
            $get = 'user';
        }
        return $this->json([
            'role' => $get
        ]);
    }

}
