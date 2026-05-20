<?php

namespace App\Controller\Admin;

use App\Entity\Position;
use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/positions')]
class AdminPositionsController extends AbstractController
{
    public function __construct(
        private readonly PositionRepository $positionRepository
    ) {
    }

    #[Route('', name: 'admin_positions_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $positions = $this->positionRepository->findBy([], ['name' => 'ASC']);
        $result = array_map(static fn(Position $position) => [
            'id' => $position->getId(),
            'name' => $position->getName(),
            'reduction' => $position->getReduction(),
        ], $positions);

        return $this->json($result);
    }

    #[Route('', name: 'admin_positions_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $name = trim((string)($data['name'] ?? ''));
        $reduction = trim((string)($data['reduction'] ?? ''));

        if ($name === '') {
            return $this->json(['error' => 'name is required'], 400);
        }

        if ($this->positionRepository->findOneBy(['name' => $name])) {
            return $this->json(['error' => 'Такая должность уже существует'], 409);
        }

        $position = (new Position())
            ->setName($name)
            ->setReduction($reduction !== '' ? $reduction : null);

        $this->positionRepository->save($position);

        return $this->json([
            'id' => $position->getId(),
            'name' => $position->getName(),
            'reduction' => $position->getReduction(),
        ], 201);
    }

    #[Route('/{id}', name: 'admin_positions_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $position = $this->positionRepository->find($id);
        if (!$position) {
            return $this->json(['error' => 'Position not found'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $name = trim((string)($data['name'] ?? ''));
        $reduction = trim((string)($data['reduction'] ?? ''));

        if ($name === '') {
            return $this->json(['error' => 'name is required'], 400);
        }

        $duplicate = $this->positionRepository->findOneBy(['name' => $name]);
        if ($duplicate && $duplicate->getId() !== $position->getId()) {
            return $this->json(['error' => 'Такая должность уже существует'], 409);
        }

        $position
            ->setName($name)
            ->setReduction($reduction !== '' ? $reduction : null);

        $this->positionRepository->save($position);

        return $this->json([
            'id' => $position->getId(),
            'name' => $position->getName(),
            'reduction' => $position->getReduction(),
        ]);
    }
}
