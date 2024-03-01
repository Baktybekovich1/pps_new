<?php

namespace App\Controller\rating;

use App\Repository\UserInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PpsRatingController extends AbstractController
{
    public function __construct(private UserInfoRepository $userInfoRepository)
    {
    }

    #[Route('/pps', name: 'app_pps_rating')]
    public function index(): JsonResponse
    {
        $pps = $this->userInfoRepository->findAll();

        return $this->json([
            'pps' => $pps
        ]);
    }
}
