<?php

namespace App\Controller;

use App\Entity\Years;
use App\Repository\YearsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

/**
 * Публичный контроллер для получения списка годов (фронт использует для переключения)
 */
#[Route('/api/years')]
#[OA\Tag(name: 'Years')]
class PublicYearsController extends AbstractController
{
    public function __construct(
        private readonly YearsRepository $yearsRepository
    ) {
    }

    /**
     * Список всех годов (публичный, без авторизации)
     */
    #[Route('', name: 'public_years_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $years = $this->yearsRepository->findAll();

        $result = array_map(fn(Years $y) => [
            'id'        => $y->getId(),
            'name'      => $y->getName(),
            'isCurrent' => (bool) $y->isCurrent(),
            'isLocked'  => (bool) $y->isLocked(),
        ], $years);

        // Сортируем: сначала текущий, затем по id desc (новые сначала)
        usort($result, function ($a, $b) {
            if ($a['isCurrent'] !== $b['isCurrent']) {
                return $b['isCurrent'] <=> $a['isCurrent'];
            }
            return $b['id'] <=> $a['id'];
        });

        return $this->json($result);
    }
}
