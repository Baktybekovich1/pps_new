<?php

namespace App\Controller\user;

use App\Dto\UserResearchActivitiesAddDto;
use App\Entity\ResearchActivitiesList;
use App\Entity\UserResearchActivities;
use App\Entity\UserResearchActivitiesAndLink;
use App\Repository\ResearchActivitiesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UserResearchController extends AbstractController
{
    public function __construct(
        private ResearchActivitiesListRepository $activitiesListRepository
    )
    {
    }

    #[Route('/research', name: 'app_user_research')]
    public function index(): JsonResponse
    {
        $activitiesList = $this->activitiesListRepository->findAll();
        return $this->json([
            'activities' => $activitiesList
        ]);
    }
    #[Route('/reseaech/add',name: 'app_user_research_add',methods: ['POST'])]
    public function research_add(#[MapRequestPayload] UserResearchActivitiesAddDto $dto):JsonResponse
    {

//        $ura = new UserResearchActivities();
//        $ura->setName($dto->name);
//
//        foreach ($dto->ural as $i) {
//            $ural = new UserResearchActivitiesAndLink();
//            $ural->setName($i[''])
//        }
        return $this->json([
            $dto->ural
        ]);

    }
}
