<?php

namespace App\Controller\admin\Expert;

use App\Dto\Expert\SetNewExpertDto;
use App\Service\Expert\AdminExpertService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AdminExpertController extends AbstractController
{
    public function __construct(private readonly AdminExpertService $adminExpertService)
    {
    }

    #[Route('/expert/add', name: 'expert_add', methods: ['POST'])]
    public function newExpertRoute(#[MapRequestPayload] SetNewExpertDto $dto): JsonResponse
    {
        return $this->json($this->adminExpertService->newExpert($dto));
    }

    #[Route('/expert/remove/{id}', name: 'expert_remove', methods: ['DELETE'])]
    public function removeExpertRoute(Request $request): JsonResponse
    {
        return $this->json($this->adminExpertService->removeExpert($request->get('id')));
    }


}