<?php

namespace App\Controller\Expert;

use App\Dto\Expert\SetPointsToUserDto;
use App\Repository\ExpertRepository;
use App\Repository\UserRepository;
use App\Service\Expert\ExpertService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use OpenApi\Attributes as OA;
#[OA\Tag(name: 'Expert docs')]
class ExpertController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ExpertService  $expertService, private readonly ExpertRepository $expertRepository
    )
    {
    }

    #[Route('/add_point', name: 'expert_add_point', methods: ['POST'])]
    public function addPointsToUser(UserInterface $user, #[MapRequestPayload] SetPointsToUserDto $dto): JsonResponse
    {
        $expert = $this->expertRepository->findOneBy(['account' => $user]);
        return $this->json($this->expertService->addExpertAddPointsToUser($expert, $this->userRepository->find($dto->userId), $dto->point));
    }

}