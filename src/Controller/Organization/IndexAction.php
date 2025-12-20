<?php
namespace App\Controller\Organization;

use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexAction extends AbstractController
{
    #[Route('/api/organizations', name: 'organization_index', methods: ['GET'])]
    public function __invoke(OrganizationRepository $repo): Response
    {
        $organizations = $repo->findBy([], ['name' => 'ASC']);
        return $this->json($organizations, 200, [], ['groups' => 'organization:read']);
    }
}