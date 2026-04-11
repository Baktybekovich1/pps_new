<?php

namespace App\Controller\admin;

use App\Entity\Years;
use App\Repository\YearsRepository;
use App\Service\Years\AddNewYearsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/admin/years')]
#[OA\Tag(name: 'Admin Years')]
class AdminYearController extends AbstractController
{
    public function __construct(
        private readonly YearsRepository $yearsRepository,
        private readonly AddNewYearsService $addNewYearsService
    ) {
    }

    /**
     * Список всех годов (с флагами isCurrent, isLocked)
     */
    #[Route('', name: 'admin_years_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $years = $this->yearsRepository->findAll();
        $result = array_map(fn(Years $y) => [
            'id'        => $y->getId(),
            'name'      => $y->getName(),
            'isCurrent' => $y->isCurrent(),
            'isLocked'  => $y->isLocked(),
        ], $years);

        // Сортируем: сначала текущий, затем по id desc
        usort($result, function ($a, $b) {
            if ($a['isCurrent'] !== $b['isCurrent']) {
                return $b['isCurrent'] <=> $a['isCurrent'];
            }
            return $b['id'] <=> $a['id'];
        });

        return $this->json($result);
    }

    /**
     * Создать новый год (архивирует текущий)
     */
    #[Route('', name: 'admin_years_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;

        if (!$name) {
            return $this->json(['error' => 'Name is required'], 400);
        }

        $success = $this->addNewYearsService->addYear($name);

        if ($success) {
            return $this->json(['message' => 'New academic year created and activated'], 201);
        }

        return $this->json(['error' => 'Failed to create new year'], 500);
    }

    /**
     * Установить год как текущий (снимает флаг у всех остальных)
     */
    #[Route('/{id}/set-current', name: 'admin_years_set_current', methods: ['PATCH'])]
    public function setCurrent(int $id): JsonResponse
    {
        $year = $this->yearsRepository->find($id);
        if (!$year) {
            return $this->json(['error' => 'Year not found'], 404);
        }

        // Сначала снимаем isCurrent у всех годов
        $allYears = $this->yearsRepository->findAll();
        foreach ($allYears as $y) {
            if ($y->isCurrent() && $y->getId() !== $id) {
                $y->setIsCurrent(false);
                $this->yearsRepository->save($y);
            }
        }

        $year->setIsCurrent(true);
        $this->yearsRepository->save($year);

        return $this->json([
            'message' => 'Year set as current',
            'id'      => $year->getId(),
            'name'    => $year->getName(),
        ]);
    }

    /**
     * Переключить блокировку года (lock/unlock)
     */
    #[Route('/{id}/lock', name: 'admin_years_lock', methods: ['PATCH'])]
    public function toggleLock(int $id): JsonResponse
    {
        $year = $this->yearsRepository->find($id);
        if (!$year) {
            return $this->json(['error' => 'Year not found'], 404);
        }

        $year->setIsLocked(!$year->isLocked());
        $this->yearsRepository->save($year);

        return $this->json([
            'message'  => $year->isLocked() ? 'Year locked' : 'Year unlocked',
            'isLocked' => $year->isLocked(),
        ]);
    }

    /**
     * Удалить год (только если не текущий)
     */
    #[Route('/{id}', name: 'admin_years_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $year = $this->yearsRepository->find($id);
        if (!$year) {
            return $this->json(['error' => 'Year not found'], 404);
        }

        if ($year->isCurrent()) {
            return $this->json(['error' => 'Cannot delete the current year. Set another year as current first.'], 400);
        }

        $this->yearsRepository->remove($year);

        return $this->json(['message' => 'Year deleted']);
    }
}
