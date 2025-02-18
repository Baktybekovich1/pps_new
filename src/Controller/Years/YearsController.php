<?php

namespace App\Controller\Years;

use App\Service\Years\AddNewYearsService;
use App\Service\Years\GetYearsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class YearsController extends AbstractController
{
    public function __construct(private readonly GetYearsService $getYearsService,
    private readonly AddNewYearsService $addNewYearsService
    )
    {
    }

    #[Route('/', name: 'app_get_years', methods: ['GET'])]
    public function getYears(): JsonResponse
    {
        return $this->json($this->getYearsService->getYears());
    }

    #[Route('/add', name: 'app_add_years', methods: ['POST'])]
    public function addYears(): JsonResponse
    {
        return $this->json($this->addNewYearsService->addYear('Text'));
    }



}