<?php

namespace App\Controller\Admin;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminOrganizationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrganizationRepository $organizationRepository
    ) {
    }

    #[Route('/organizations', name: 'admin_organization_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $organization = new Organization();
        
        $name = $request->request->get('name');
        if (!$name) {
            return $this->json(['error' => 'Название организации обязательно'], 400);
        }
        
        $organization->setName($name);
        
        if ($request->files->has('photoFile')) {
            $organization->setPhotoFile($request->files->get('photoFile'));
        }

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        return $this->json(['message' => 'Organization created', 'id' => $organization->getId()]);
    }

    #[Route('/organizations/{id}', name: 'admin_organization_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $organization = $this->organizationRepository->find($id);
        if (!$organization) {
            return $this->json(['error' => 'Organization not found'], 404);
        }

        $name = $request->request->get('name');
        if ($name) {
            $organization->setName($name);
        }

        if ($request->files->has('photoFile')) {
            $organization->setPhotoFile($request->files->get('photoFile'));
        }

        // if there's no photo file being uploaded, VichUploader will keep the old one.
        // It's a standard behavior.

        $this->entityManager->flush();

        return $this->json(['message' => 'Organization updated']);
    }

    #[Route('/organizations/{id}', name: 'admin_organization_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $organization = $this->organizationRepository->find($id);
        if (!$organization) {
            return $this->json(['error' => 'Organization not found'], 404);
        }

        try {
            $this->entityManager->remove($organization);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Delete failed',
                'message' => $e->getMessage()
            ], 500);
        }

        return $this->json(['message' => 'Organization deleted']);
    }
}
