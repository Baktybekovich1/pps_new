<?php

namespace App\Controller\admin;

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

class AccountActiveController extends AbstractController
{
    public function __construct(
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }

    #[Route('/award/active', name: 'app_admin_award_active', methods: ['PUT'])]
    public function award_active(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $award = $this->userPersonalAwardsRepository->find($id);
            $award->setStatus('active');
            $this->userPersonalAwardsRepository->save($award);
        }
        return $this->json(['Success']);
    }

    #[Route('/research/active', name: 'app_admin_research_active', methods: ['PUT'])]
    public function research_active(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $award = $this->userResearchActivitiesListRepository->find($id);
            $award->setStatus('active');
            $this->userResearchActivitiesListRepository->save($award);
        }
        return $this->json(['Success']);
    }


    #[Route('/innovative/active', name: 'app_admin_innovative_active', methods: ['PUT'])]
    public function innovative_active(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $innovative = $this->userInnovativeEducationRepository->find($id);
            $innovative->setStatus('active');
            $this->userInnovativeEducationRepository->save($innovative);

        }
        return $this->json(['Success']);
    }

    #[Route('/social/active', name: 'app_admin_social_active', methods: ['PUT'])]
    public function social_active(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $social = $this->userSocialActivitiesRepository->find($id);
            $social->setStatus('active');
            $this->userSocialActivitiesRepository->save($social);

        }
        return $this->json(['Success']);
    }
}
