<?php

namespace App\Controller\admin;

use App\Dto\OffencesDto\UserOffenceAddDto;
use App\Dto\OffencesDto\UserOffenceGetDto;
use App\Dto\OffencesDto\UserOffencesDto;
use App\Entity\UserOffence;
use App\Repository\OffenceListRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class OffenceController extends AbstractController
{
    public function __construct(
        private OffenceListRepository $offenceListRepository,
        private UserOffenceRepository $userOffenceRepository,
        private UserRepository        $userRepository,
        private UserInfoRepository    $userInfoRepository
    )
    {
    }

    #[Route('/offence', name: 'app_offence')]
    public function index(): JsonResponse
    {
        $offence = $this->offenceListRepository->findAll();
        $users = $this->userInfoRepository->findAll();
        $dto = [];
        foreach ($users as $user) {
            $off = [];
            foreach ($offence as $item) {
                $off[] = new UserOffencesDto(
                    $item->getId(),
                    $item->getName()
                );
            }
            $dto[] = new UserOffenceGetDto(
                $user->getId(),
                $user->getName(),
                $off
            );

        }
        return $this->json([
            'offence' => $dto
        ]);
    }

    #[Route('/offence/add', name: 'app_offence_add', methods: ['POST'])]
    public function add(#[MapRequestPayload] UserOffenceAddDto $dto): JsonResponse
    {
        $offences = $dto->offence;
        foreach ($offences as $offence) {
            $oldOffence = $this->userOffenceRepository->findOneBy(['user' => $this->userRepository->find($offence['userId'])]);
            $newOffence = new UserOffence();
            $newOffence->setUser($this->userRepository->find($offence['userId']));
            $newOffence->setQuantity($offence['quantity']);
            $newOffence->setOffenceList($this->offenceListRepository->find($offence['id']));
            $this->userOffenceRepository->save($newOffence);
        }


        return $this->json([
            'Success'
        ]);
    }

}
