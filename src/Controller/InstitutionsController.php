<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class InstitutionsController extends AbstractController
{
    #[Route('/institutions', name: 'app_institutions')]
    public function index(): JsonResponse
    {

        return $this->json([
            ''
        ]);
    }
}
