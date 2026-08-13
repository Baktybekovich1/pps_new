<?php

namespace App\Controller\Admin;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use App\Security\OrganizationVoter;
use App\Tenant\OrganizationContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AdminOrganizationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrganizationRepository $organizationRepository
    ) {
    }

    #[Route('/organizations', name: 'api_admin_organization_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Bringing a new tenant into existence is not something the admin of an
        // existing one gets to do.
        if (!$this->isGranted(OrganizationContext::ROLE_CROSS_ORGANIZATION)) {
            return $this->json(['error' => 'Access denied'], 403);
        }
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

    #[Route('/organizations/{id}', name: 'api_admin_organization_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $organization = $this->organizationRepository->find($id);
        if (!$organization) {
            return $this->json(['error' => 'Organization not found'], 404);
        }

        // Organization is the tenant itself, so OrganizationFilter never
        // constrains it — an id pointing at someone else's organization has to
        // be rejected here.
        if (!$this->isGranted(OrganizationVoter::ACCESS, $organization)) {
            return $this->json(['error' => 'Access denied'], 403);
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

    #[Route('/organizations/{id}', name: 'api_admin_organization_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        // Deleting an organization cascades away everything that belongs to it.
        if (!$this->isGranted(OrganizationContext::ROLE_CROSS_ORGANIZATION)) {
            return $this->json(['error' => 'Access denied'], 403);
        }
        try {
            $organization = $this->organizationRepository->find($id);
            if (!$organization) {
                return $this->json(['error' => 'Organization not found'], 404);
            }

            $this->entityManager->remove($organization);
            $this->entityManager->flush();
            
            return $this->json(['message' => 'Organization deleted']);
        } catch (\Throwable $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Delete failed aggressively',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            exit;
        }
    }
}
