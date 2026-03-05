<?php

namespace App\Controller\Director;

use App\Service\Director\DirectorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DirectorController extends AbstractController
{
    public function __construct(private readonly DirectorService $directorService)
    {
    }
    #[Route(path: '/', name: 'find_all', methods: ['GET'])]
    public function get_all(): JsonResponse
    {
        return $this->json($this->directorService->findAllDirectors());
    }

}