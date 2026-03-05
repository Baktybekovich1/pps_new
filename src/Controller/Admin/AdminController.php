<?php

namespace App\Controller\Admin;


use App\Dto\Admin\AddDirectorDto;
use App\Service\Admin\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
#[OA\Tag(name: 'Admin', description: 'Admin api')]
class AdminController extends AbstractController
{

    public function __construct(private readonly AdminService $adminService)
    {
    }

    #[Route(path: '/director', name: 'new_director', methods: ['POST'])]
    public function new_director(#[MapRequestPayload] AddDirectorDto $dto): JsonResponse
    {
        return $this->json($this->adminService->addDirector($dto->teacherId, $dto->instituteId));
    }

    #[Route(path: '/director/{directorId}', name: 'remove_director', methods: ['DELETE'])]
    public function remove_director(Request $request): JsonResponse
    {
        return $this->json($this->adminService->removeDirector($request->get('directorId')));
    }


}