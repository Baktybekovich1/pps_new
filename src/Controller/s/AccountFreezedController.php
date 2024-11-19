<?php

namespace App\Controller\s;

use App\Dto\AdminFreezeSetAwardDto;
use App\Dto\UserAccount\AccountFreezedDto;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountFreezedController extends AbstractController
{
    public function __construct(
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }

    #[Route('/award/freeze', name: 'app_admin_award_freeze', methods: ['PUT'])]
    public function award_freeze(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $award = $this->userPersonalAwardsRepository->find($id);
            $award->setStatus('freeze');
            $this->userPersonalAwardsRepository->save($award);
        }
        return $this->json(['Success']);
    }

    #[Route('/research/freeze', name: 'app_admin_research_freeze', methods: ['PUT'])]
    public function research_freeze(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $award = $this->userResearchActivitiesListRepository->find($id);
            $award->setStatus('freeze');
            $this->userResearchActivitiesListRepository->save($award);

        }
        return $this->json(['Success']);
    }


    #[Route('/innovative/freeze', name: 'app_admin_innovative_freeze', methods: ['PUT'])]
    public function innovative_freeze(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $innovative = $this->userInnovativeEducationRepository->find($id);
            $innovative->setStatus('freeze');
            $this->userInnovativeEducationRepository->save($innovative);

        }
        return $this->json(['Success']);
    }

    #[Route('/social/freeze', name: 'app_admin_social_freeze', methods: ['PUT'])]
    public function social_freeze(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $social = $this->userSocialActivitiesRepository->find($id);
            $social->setStatus('freeze');
            $this->userSocialActivitiesRepository->save($social);

        }
        return $this->json(['Success']);
    }
}
