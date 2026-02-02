<?php
namespace App\Controller\Organization;

use App\Entity\Organization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class InstitutesAction extends AbstractController
{
    #[Route('/api/organizations/{id}/institutes', name: 'organization_institutes', methods: ['GET'])]
    public function __invoke(Organization $org): Response
    {
        // ParamConverter сам найдёт по id, 404 если нет
        $institutes = $org->getInstitutes(); // отсортировано можно в репо
        return $this->json($institutes, 200, [], ['groups' => 'institute:read']);
    }
}