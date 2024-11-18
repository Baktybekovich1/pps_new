<?php

namespace App\Controller\user;

use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UserAccountAwardsGetController extends AbstractController
{
    public function __construct(
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }


    #[Route('/account/research/get/{id}', name: 'app_user_account_research_get',methods: ['GET'])]
    public function research(Request $request): JsonResponse
    {
        $award = $this->userResearchActivitiesListRepository->find($request->get('id'));
        $name = $award->getSubtitle()->getCategory()->getName() . ': ' . $award->getSubtitle()->getName();

        return $this->json([
            "name" => $name,
            "link" => $award->getLink(),
            "stage" => "research"
        ]);
    }

    #[Route('/account/innovative/get/{id}', name: 'app_user_account_innovative_get',methods: ['GET'])]
    public function innovative(Request $request): JsonResponse
    {
        $award = $this->userInnovativeEducationRepository->find($request->get('id'));
        $name = $award->getInnovativeEducationSubtitle()->getTitle()->getName() . ': ' . $award->getInnovativeEducationSubtitle()->getName();

        return $this->json([
            "name" => $name,
            "link" => $award->getLink(),
            "stage" => "innovative"
        ]);
    }

    #[Route('/account/social/get/{id}', name: 'app_user_account_social_get' ,methods: ['GET'])]
    public function social(Request $request): JsonResponse
    {
        $award = $this->userSocialActivitiesRepository->find($request->get('id'));
        $name = $award->getSocialActivitiesSubtitle()->getTitle()->getName() . ': ' . $award->getSocialActivitiesSubtitle()->getName();

        return $this->json([
            "name" => $name,
            "link" => $award->getLink(),
            "stage" => "social"
        ]);
    }

}
