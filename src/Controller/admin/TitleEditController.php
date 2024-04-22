<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TitleEditController extends AbstractController
{
    #[Route('/award/title/edit/{id}', name: 'app_title_edit')]
    public function index(Request $request): JsonResponse
    {

        return $this->json([

        ]);
    }
}
