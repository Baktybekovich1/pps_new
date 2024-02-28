<?php

namespace App\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(UserInterface $users): JsonResponse
    {
        return $this->json([
            'message' => 'Вы админ',
            'name' => $users->getRoles()
        ]);
    }
}
