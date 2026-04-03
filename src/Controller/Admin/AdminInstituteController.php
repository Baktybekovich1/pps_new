<?php

namespace App\Controller\Admin;

use App\Entity\Institute;
use App\Repository\InstituteRepository;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
class AdminInstituteController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly InstituteRepository $instituteRepository,
        private readonly OrganizationRepository $organizationRepository
    ) {
    }

    #[Route('/institutes', name: 'api_admin_institute_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['organization_id'])) {
            return $this->json(['error' => 'Name and organization_id are required'], 400);
        }

        $organization = $this->organizationRepository->find($data['organization_id']);
        if (!$organization) {
            return $this->json(['error' => 'Organization not found'], 404);
        }

        $institute = new Institute();
        $institute->setName($data['name']);
        $institute->setOrganization($organization);
        
        if (isset($data['reduction'])) {
            $institute->setReduction($data['reduction']);
        }
        
        if (isset($data['teacherTotal'])) {
            $institute->setTeacherTotal((int)$data['teacherTotal']);
        }

        $this->entityManager->persist($institute);
        $this->entityManager->flush();

        return $this->json(['message' => 'Institute created', 'id' => $institute->getId()]);
    }

    #[Route('/institutes/{id}', name: 'api_admin_institute_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }
        $institute = $this->instituteRepository->find($id);
        if (!$institute) {
            return $this->json(['error' => 'Institute not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $institute->setName($data['name']);
        }

        if (isset($data['reduction'])) {
            $institute->setReduction($data['reduction']);
        }
        
        // Explicitly handle nulling reduction if requested
        if (array_key_exists('reduction', $data) && $data['reduction'] === null) {
            $institute->setReduction(null);
        }

        if (isset($data['teacherTotal'])) {
            $institute->setTeacherTotal((int)$data['teacherTotal']);
        }
        
        if (array_key_exists('teacherTotal', $data) && $data['teacherTotal'] === null) {
            $institute->setTeacherTotal(null);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Institute updated']);
    }

    #[Route('/institutes/{id}', name: 'api_admin_institute_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }
        $institute = $this->instituteRepository->find($id);
        if (!$institute) {
            return $this->json(['error' => 'Institute not found'], 404);
        }

        $this->entityManager->remove($institute);
        $this->entityManager->flush();

        return $this->json(['message' => 'Institute deleted']);
    }
}
