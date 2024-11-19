<?php

namespace App\Controller;

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

    #[Route('/api/admin/offence', name: 'app_offence')]
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
                $user->getUser()->getId(),
                $user->getName(),
                $off
            );

        }
        return $this->json([
            'offence' => $dto
        ]);
    }

    #[Route('/api/admin/offence/add', name: 'app_offence_add', methods: ['POST'])]
    public function add(#[MapRequestPayload] UserOffenceAddDto $dto): JsonResponse
    {
        $offences = $dto->offence;
        $isset = False;
        foreach ($offences as $offence) {
            $oldOffence = $this->userOffenceRepository->findBy(['user' => $this->userRepository->find($offence['userId'])]);
            foreach ($oldOffence as $item) {
                if ($item->getOffenceList()->getId() == $offence['id']) {
                    $isset = True;
                    $off = $item;
                    break;
                }
            }
            if (!$isset) {
                $newOffence = new UserOffence();
                $newOffence->setUser($this->userRepository->find($offence['userId']));
                $newOffence->setQuantity($offence['quantity']);
                $newOffence->setOffenceList($this->offenceListRepository->find($offence['id']));
                $this->userOffenceRepository->save($newOffence);
            }
            else {
                $off->setQuantity($off->getQuantity()+$offence['quantity']);
                $this->userOffenceRepository->save($off);
            }
        }


        return $this->json([
            'Success'
        ]);
    }

}
