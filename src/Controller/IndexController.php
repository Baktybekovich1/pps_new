<?php

namespace App\Controller;

use App\Repository\ComtehnoPpsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(private ComtehnoPpsRepository $ppsRepository)
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            $this->ppsRepository->findAll()
        ]);
    }
}
