<?php

namespace App\Controller\user;

use App\Dto\ResearchGetSub;
use App\Dto\UserResearchActivitiesAddDto;
use App\Dto\UserResearchGetDto;
use App\Entity\ResearchActivitiesList;
use App\Entity\ResearchActivitiesSubtitle;
use App\Entity\UserResearchActivitiesList;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserResearchActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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

    #[Route('/research', name: 'app_user_research')]
    public function index(): JsonResponse
    {
        $ural = $this->activitiesListRepository->findAll();
        $uu = [];
        $sub = [];
        foreach ($ural as $ura) {
            if ($ura->getName() == 'Индекс Хирша по публикациям' or $ura->getName() == 'Публикации в базах Scopus и/или Web of Science') {
                continue;
            } else {
                foreach ($ura->getResearchActivitiesSubtitles() as $subtitle) {
                    $id = $subtitle->getId();
                    $name = $subtitle->getName();

                    if ($name == null) {
                        $name = 'Выбрать';
                    }
                    $sub[] = new ResearchGetSub(
                        $id,
                        $name,
                        'a'
                    );
                }
                $uu[] = new UserResearchGetDto(
                    $ura->getId(),
                    $ura->getName(),
                    $sub
                );
                $sub = [];

            }
        }

        return $this->json([$uu]);
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
            $this->uralRepository->save($ural);
        }


        return $this->json([
            "SUCCESS"
        ]);

    }
}
