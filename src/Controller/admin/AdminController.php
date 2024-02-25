<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Вы админ'
        ]);
    }
}
