<?php

namespace App\Controller\user;

use App\Dto\UserResearchActivitiesAddDto;
use App\Entity\ResearchActivitiesList;
use App\Entity\ResearchActivitiesSubtitle;
use App\Entity\UserResearchActivitiesList;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
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
        private ResearchActivitiesListRepository $activitiesListRepository,
        private UserResearchActivitiesListRepository $uralRepository

    )
    {
    }

    #[Route('/research', name: 'app_user_research')]
    public function index(): JsonResponse
    {
        $one = $this->activitiesListRepository->find(12);
        $two = $this->activitiesListRepository->find(13);
        $three = $this->activitiesListRepository->find(14);
        $four = $this->activitiesListRepository->find(15);
        $five = $this->activitiesListRepository->find(16);
        $six = $this->activitiesListRepository->find(17);
        $seven = $this->activitiesListRepository->find(18);

        return $this->json([
            'one' => $one,
            'two' => $two,
            'three' => $three,
            'four' => $four,
            'five' => $five,
            'six' => $six,
            'seven' => $seven
        ]);
    }
    #[Route('/research/add',name: 'app_user_research_add',methods: ['POST'])]
    public function research_add(UserInterface $user,#[MapRequestPayload] UserResearchActivitiesAddDto $dto):JsonResponse
    {
        foreach ($dto->ural as $item)
        {
            $ural = new UserResearchActivitiesList();
            $ural->setUserId($user->getUserIdentifier());
            $ural->setRalId($item['subId']);
            $ural->setLink($item['link']);
            $this->uralRepository->save($ural);
        }




        return $this->json([
            "SUCCESS"
        ]);

    }
}
