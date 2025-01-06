<?php

namespace App\Controller\user;

use App\Dto\UserStagesAdd\UserResearchActivitiesAddDto;
use App\Entity\UserResearchActivitiesList;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users Research')]
class UserResearchController extends AbstractController
{
    public function __construct(
        private ResearchActivitiesListRepository     $activitiesListRepository,
        private UserResearchActivitiesListRepository $uralRepository,
        private UserRepository                       $userRepository,
        private ResearchActivitiesSubtitleRepository $subtitleRepository

    )
    {
    }

    #[Route('/research', name: 'app_user_research',methods:['GET'])]
    public function index(): JsonResponse
    {
        $ural = $this->activitiesListRepository->findAll();

        return $this->json([$ural]);
    }

    #[Route('/research/add', name: 'app_user_research_add', methods: ['POST'])]
    public function research_add(UserInterface $userStorage, #[MapRequestPayload] UserResearchActivitiesAddDto $dto): JsonResponse
    {

        $user = $this->userRepository->find($userStorage->getUserIdentifier());
        foreach ($dto->ural as $item) {
            $ural = new UserResearchActivitiesList();
            $ural->setUser($user);
            $ural->setSubtitle($this->subtitleRepository->find($item['subId']));
            $ural->setLink($item['link']);
            $ural->setStatus('active');
            $this->uralRepository->save($ural);
        }
        return $this->json([
            "SUCCESS"
        ]);
    }
}
