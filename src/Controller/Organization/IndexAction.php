<?php
namespace App\Controller\Organization;

use App\Dto\Institute\InstituteDto;
use App\Repository\OrganizationRepository;
use App\Service\Organization\OrganizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController
{
    public function __construct(
        private readonly OrganizationService $organizationService,

    )
    {
    }

    #[Route('/api/organizations', name: 'organization_index', methods: ['GET'])]
    public function __invoke(OrganizationRepository $repo): Response
    {
        $organizations = $repo->findBy([], ['name' => 'ASC']);
        return $this->json($organizations, 200, [], ['groups' => 'organization:read']);
    }

    #[Route('/api/organizations/{id}', name: 'organization', methods: ['GET'])]
    public function organization(Request $request): JsonResponse
    {
        return $this->json($this->organizationService->getOrganization($request->get('id')));
    }
}