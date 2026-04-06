<?php

namespace App\Controller\admin;

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

    #[Route('', name: 'admin_years_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $years = $this->yearsRepository->findAll();
        return $this->json($years, 200, [], ['groups' => 'year:read']);
    }

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
            'message' => $year->isLocked() ? 'Year locked' : 'Year unlocked',
            'isLocked' => $year->isLocked()
        ]);
    }
}
