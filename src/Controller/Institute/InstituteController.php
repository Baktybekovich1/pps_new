<?php

namespace App\Controller\Institute;

use App\Service\Instiute\InstituteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
#[OA\Tag(name: 'Institute', description: 'Institute api')]
class InstituteController extends AbstractController
{

    public function __construct(
        private readonly InstituteService $instituteService
    )
    {
    }
    #[Route(path: '/', name: 'get_institutes', methods: ['GET'])]
    public function get(): JsonResponse
    {
        return $this->json($this->instituteService->getAllInstitutes());
    }

}