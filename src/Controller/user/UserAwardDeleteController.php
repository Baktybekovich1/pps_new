<?php

namespace App\Controller\user;

use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAwardDeleteController extends AbstractController
{
    public function __construct(
        private readonly UserRepository                       $userRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository,
    )
    {
    }

    #[Route('/award/delete/{id}', name: 'app_user_award_delete', methods: ['DELETE'])]
    public function award(UserInterface $userStorage, Request $request): JsonResponse
    {
        $award = $this->userPersonalAwardsRepository->find($request->get('id'));
        $user = $this->userRepository->find($userStorage->getUserIdentifier());
        if ($award->getUser()->getId() == $user->getId()) {
            $this->userPersonalAwardsRepository->remove($award);
        } else {
            return $this->json(['У вас нет доступа к этой награде!']);
        }

        return $this->json([
            "Delete SUCCESS"
        ]);
    }

    #[Route('/research/delete/{id}', name: 'app_user_research_delete', methods: ['DELETE'])]
    public function research(UserInterface $userStorage, Request $request): JsonResponse
    {
        $research = $this->userResearchActivitiesListRepository->find($request->get('id'));
        $user = $this->userRepository->find($userStorage->getUserIdentifier());
        if ($research->getUser()->getId() == $user->getId()) {
            $this->userResearchActivitiesListRepository->remove($research);
        } else {
            return $this->json(['У вас нет доступа к этой награде!']);
        }

        return $this->json([
            "Delete SUCCESS"
        ]);
    }


    #[Route('/innovative/delete/{id}', name: 'app_user_innovative_delete', methods: ['DELETE'])]
    public function innovative(UserInterface $userStorage, Request $request): JsonResponse
    {
        $innovative = $this->userInnovativeEducationRepository->find($request->get('id'));
        $user = $this->userRepository->find($userStorage->getUserIdentifier());
        if ($innovative->getUser()->getId() == $user->getId()) {
            $this->userInnovativeEducationRepository->remove($innovative);
        } else {
            return $this->json(['У вас нет доступа к этой награде!']);
        }

        return $this->json([
            "Delete SUCCESS"
        ]);
    }

    #[Route('/social/delete/{id}', name: 'app_user_social_delete', methods: ['DELETE'])]
    public function social(UserInterface $userStorage, Request $request): JsonResponse
    {
        $social = $this->userSocialActivitiesRepository->find($request->get('id'));
        $user = $this->userRepository->find($userStorage->getUserIdentifier());
        if ($social->getUser()->getId() == $user->getId()) {
            $this->userSocialActivitiesRepository->remove($social);
        } else {
            return $this->json(['У вас нет доступа к этой награде!']);
        }

        return $this->json([
            "Delete SUCCESS"
        ]);
    }


}
