<?php

namespace App\Controller\s;

use App\Dto\AdditionalDto;
use App\Entity\UserOffence;
use App\Entity\UserResearchActivitiesList;
use App\Repository\OffenceListRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdditionalController extends AbstractController
{
    public function __construct(
        private ResearchActivitiesListRepository     $researchActivitiesListRepository,
        private OffenceListRepository                $offenceListRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserRepository                       $userRepository,
        private ResearchActivitiesSubtitleRepository $researchActivitiesSubtitleRepository,
        private UserOffenceRepository                $userOffenceRepository
    )
    {
    }

    #[Route('/additional', name: 'app_additional')]
    public function index(): JsonResponse
    {

        $offence = $this->offenceListRepository->findAll();
        return $this->json([
            'offence' => $offence
        ]);
    }

    #[Route('/additional/add', name: 'app_additional_add')]
    public function add(UserInterface $user, #[MapRequestPayload] AdditionalDto $dto): JsonResponse
    {
        $user = $this->userRepository->find($user->getUserIdentifier());
        foreach ($dto->awards as $item) {
            $ural = new UserResearchActivitiesList();
            $ural->setUser($user);
            $ural->setSubtitle($this->researchActivitiesSubtitleRepository->find($item['subId']));
            $ural->setLink($item['link']);
            $this->userResearchActivitiesListRepository->save($ural);
        }
        foreach ($dto->offence as $off) {
            $userOff = new UserOffence();
            $userOff->setUser($user);
            $userOff->setOffenceList($off['subId']);
            $this->userOffenceRepository->save($userOff);
        }

        return $this->json(['Success~']);

    }
}
