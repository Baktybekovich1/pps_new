<?php
namespace App\Controller\Organization;

use App\Entity\Organization;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class InstitutesAction extends AbstractController
{
    // The placeholder is named orgId so OrganizationFilterConfigurator picks it
    // up: this endpoint is about the organization in the URL, and without that
    // a visitor from another organization would get an empty list. The URL
    // itself is unchanged.
    #[Route('/api/organizations/{orgId}/institutes', name: 'organization_institutes', methods: ['GET'])]
    public function __invoke(#[MapEntity(mapping: ['orgId' => 'id'])] Organization $org): Response
    {
        // ParamConverter сам найдёт по id, 404 если нет
        $institutes = $org->getInstitutes(); // отсортировано можно в репо
        return $this->json($institutes, 200, [], ['groups' => 'institute:read']);
    }
}