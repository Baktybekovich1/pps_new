<?php

namespace App\Controller\Years;

use App\Dto\NameDto;
use App\Service\Years\AddNewYearsService;
use App\Service\Years\GetYearsService;
use App\Service\Years\RemoveYearService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Year')]
class YearsController extends AbstractController
{
    public function __construct(private readonly GetYearsService    $getYearsService,
                                private readonly AddNewYearsService $addNewYearsService,
                                private readonly RemoveYearService  $removeYearService
    )
    {
    }

    #[Route('/', name: 'app_get_years', methods: ['GET'])]
    public function getYears(): JsonResponse
    {
        return $this->json($this->getYearsService->getYears());
    }

    #[Route('/add', name: 'app_add_years', methods: ['POST'])]
    public function addYears(#[MapRequestPayload] NameDto $dto): JsonResponse
    {
        return $this->json($this->addNewYearsService->addYear($dto->name));
    }

    #[Route('/remove/{id}', name: 'app_remove_years', methods: ['DELETE'])]
    public function removeYears(Request $request): JsonResponse
    {
        return $this->json($this->removeYearService->removeYears($request->get('id')));
    }


}