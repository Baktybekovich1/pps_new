<?php

namespace App\Controller\user;

use App\Repository\AcademicDegreeRepository;
use App\Repository\AcademicRankRepository;
use App\Repository\StateAwardsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        private AcademicDegreeRepository $degreeRepository,
        private AcademicRankRepository   $rankRepository,
        private StateAwardsRepository    $stateAwardsRepository
    )
    {
    }

    #[Route('/fill', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'degree' => $this->degreeRepository->findAll(),
            'rank' => $this->rankRepository->findAll(),
            'state_awards' => $this->stateAwardsRepository->findAll()
        ]);
    }


}
