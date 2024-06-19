<?php

namespace App\Controller\user;

use App\Dto\AdminFreezeSetAwardDto;
use App\Dto\UserAccount\UserAwardsGetDto;
use App\Dto\UserAccount\UserResearchGetDto;
use App\Repository\UserInfoRepository;
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

class UserFreezedAwardsController extends AbstractController
{
    public function __construct(
        private UserRepository                                $userRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }

    #[Route('/award/freeze', name: 'app_award_freeze', methods: ['PUT'])]
    public function award_freeze(UserInterface $userInterface, #[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {

            $award = $this->userPersonalAwardsRepository->find($id);
            $user = $this->userRepository->find($userInterface->getUserIdentifier());
            if ($award->getUser()->getId() == $user->getId()) {
                $award->setStatus('freeze');
                $this->userPersonalAwardsRepository->save($award);
            } else {
                $this->createAccessDeniedException();
            }
        }
        return $this->json(['Success']);
    }

    #[Route('/research/freeze', name: 'app_research_freeze', methods: ['PUT'])]
    public function research_freeze(UserInterface $userInterface, #[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $award = $this->userResearchActivitiesListRepository->find($id);
            $user = $this->userRepository->find($userInterface->getUserIdentifier());
            if ($award->getUser()->getId() == $user->getId()) {
                $award->setStatus('freeze');
                $this->userResearchActivitiesListRepository->save($award);
            } else {
                $this->createAccessDeniedException();
            }

        }
        return $this->json(['Success']);
    }


    #[Route('/innovative/freeze', name: 'app__innovative_freeze', methods: ['PUT'])]
    public function innovative_freeze(UserInterface $userInterface, #[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $innovative = $this->userInnovativeEducationRepository->find($id);
            $user = $this->userRepository->find($userInterface->getUserIdentifier());
            if ($innovative->getUser()->getId() == $user->getId()) {
                $innovative->setStatus('freeze');
                $this->userInnovativeEducationRepository->save($innovative);
            } else {
                $this->createAccessDeniedException();
            }

        }
        return $this->json(['Success']);
    }

    #[Route('/social/freeze', name: 'app_social_freeze', methods: ['PUT'])]
    public function social_freeze(UserInterface $userInterface, #[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $social = $this->userSocialActivitiesRepository->find($id);
            $user = $this->userRepository->find($userInterface->getUserIdentifier());
            if ($social->getUser()->getId() == $user->getId()) {
                $social->setStatus('freeze');
                $this->userSocialActivitiesRepository->save($social);
            } else {
                $this->createAccessDeniedException();
            }

        }
        return $this->json(['Success']);
    }
}
