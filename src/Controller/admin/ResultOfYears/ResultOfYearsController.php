<?php

namespace App\Controller\admin\ResultOfYears;

use App\Dto\RatingDto\UserPointsCountDto;
use App\Dto\ResultsOfYears\YearDto;
use App\Entity\ResultsOfYear;
use App\Entity\Years;
use App\Repository\ResultsOfYearRepository;
use App\Repository\UserRepository;
use App\Repository\YearsRepository;
use App\Service\UserPointsCountSumService;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ResultOfYearsController extends AbstractController
{
    public function __construct(
        private readonly UserPointsCountSumService $userPointsCountSumService,
        private readonly ResultsOfYearRepository   $resultsOfYearRepository,
        private readonly YearsRepository           $yearsRepository)
    {
    }

    #[Route('/admin/years', name: 'app_result_of_years', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $years = $this->yearsRepository->findAll();
        $mass = [];
        foreach ($years as $year) {
            $mass[$year->getId()] = $year->getName();
        }
        return $this->json(['years' => $mass]);
    }

    #[Route('/admin/result-of-years/add', name: 'result-of-years', methods: ['POST'])]
    public function indexAction(#[MapRequestPayload] YearDto $dto): JsonResponse
    {
        $year = new Years();
        $year->setName($dto->year);
        $this->yearsRepository->save($year);
        $users = $this->userPointsCountSumService->UserPointsCount();
        foreach ($users as $item) {
            $ResultOfYear = new ResultsOfYear();
            $ResultOfYear->setYear($year);
            $ResultOfYear->setPoints($item->points);
            $ResultOfYear->setAccount($item->user);
            $this->resultsOfYearRepository->save($ResultOfYear);
        }
        return $this->json(['Success']);
    }

//    #[Route('/admin/result-of-years/delete', name: 'result-of-years-delete',methods: ['DELETE'])]
//    public function delete(#[MapRequestPayload] YearDto $dto): JsonResponse
//    {
//        $year = new Years();
//        $year->setName($dto->year);
//        $this->yearsRepository->save($year);
//        $users = $this->userPointsCountSumService->UserPointsCount();
//        foreach ($users as $item) {
//            $ResultOfYear = new ResultsOfYear();
//            $ResultOfYear->setYear($year);
//            $ResultOfYear->setPoints($item->points);
//            $ResultOfYear->setAccount($item->user);
//            $this->resultsOfYearRepository->save($ResultOfYear);
//        }
//        return $this->json(['Success']);
//    }
}