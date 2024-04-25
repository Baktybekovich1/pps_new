<?php

namespace App\Controller\user;

use App\Dto\AdminFreezeSetAwardDto;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAccountAwardsDeleteController extends AbstractController
{

    public function __construct(
        private readonly UserRepository              $userRepository,
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }


    #[Route('/account/award/delete', name: 'app_user_account_award_delete', methods: ['DELETE'])]
    public function award(#[MapRequestPayload] AdminFreezeSetAwardDto $dto, UserInterface $userInterface): JsonResponse
    {
        $user = $this->userRepository->find($userInterface->getUserIdentifier());
        foreach ($dto->idBag as $id) {
            if (!$this->delete($user, $this->userPersonalAwardsRepository, $id)) {
                return $this->json(['ERROR']);
            }
        }
        return $this->json([
            'Success'
        ]);
    }


    #[Route('/account/research/delete', name: 'app_user_account_research_delete', methods: ['DELETE'])]
    public function research(#[MapRequestPayload] AdminFreezeSetAwardDto $dto, UserInterface $userInterface): JsonResponse
    {
        $user = $this->userRepository->find($userInterface->getUserIdentifier());
        foreach ($dto->idBag as $id) {
            if (!$this->delete($user, $this->userResearchActivitiesListRepository, $id)) {
                return $this->json(['ERROR']);
            }
        }
        return $this->json([
            'Success'
        ]);
    }

    #[Route('/account/innovative/delete', name: 'app_user_account_innovative_delete', methods: ['DELETE'])]
    public function innovative(#[MapRequestPayload] AdminFreezeSetAwardDto $dto, UserInterface $userInterface): JsonResponse
    {
        $user = $this->userRepository->find($userInterface->getUserIdentifier());
        foreach ($dto->idBag as $id) {
            if (!$this->delete($user, $this->userInnovativeEducationRepository, $id)) {
                return $this->json(['ERROR']);
            }
        }
        return $this->json([
            'Success'
        ]);
    }

    #[Route('/account/social/delete', name: 'app_user_account_social_delete', methods: ['DELETE'])]
    public function social(#[MapRequestPayload] AdminFreezeSetAwardDto $dto, UserInterface $userInterface): JsonResponse
    {
        $user = $this->userRepository->find($userInterface->getUserIdentifier());
        foreach ($dto->idBag as $id) {
            if (!$this->delete($user, $this->userSocialActivitiesRepository, $id)) {
                return $this->json(['ERROR']);
            }
        }
        return $this->json([
            'Success'
        ]);
    }


    public function delete($user, $repository, $id): bool
    {
        $award = $repository->find($id);
        $us = $award->getUser();
        if ($us === $user) {
            $repository->remove($award);
            return True;
        } else {
            return False;
        }
    }

}
