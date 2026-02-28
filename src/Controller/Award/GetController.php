<?php

declare(strict_types=1);

namespace App\Controller\Award;

use App\Service\Award\AwardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
#[OA\Tag(name: 'Award', description: 'Award Routes')]
class GetController extends AbstractController
{

    public function __construct(
        private readonly AwardService $awardService
    )
    {
    }

    #[Route('/all', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->awardService->getAll());
    }
}
