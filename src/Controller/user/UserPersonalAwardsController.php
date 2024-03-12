<?php

namespace App\Controller\user;

use App\Dto\UserProgressDto;
use App\Entity\PersonalAwards;
use App\Entity\UserPersonalAwards;
use App\Repository\PersonalAwardsRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPersonalAwardsController extends AbstractController
{
    public function __construct(
        private UserInfoRepository       $userInfoRepository,
        private UserRepository $userRepository,
        private PersonalAwardsRepository $personalAwardsRepository
    )
    {
    }

    #[Route('/progress', name: 'app_user_progress')]
    public function index(): JsonResponse
    {
        $personalAwards = $this->personalAwardsRepository->findAll();
        return $this->json([
            $personalAwards
        ]);
    }

    #[Route('/progress/add', name: 'app_user_progress_add')]
    public function prog_add(#[MapRequestPayload] UserProgressDto $dto, UserInterface $user): JsonResponse
    {


        return $this->json([
            'Всё ОКЕЙ!'
        ]);
    }


}
