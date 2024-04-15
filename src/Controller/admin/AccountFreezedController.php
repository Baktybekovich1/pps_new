<?php

namespace App\Controller\admin;

use App\Dto\UserAccount\AccountFreezedDto;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountFreezedController extends AbstractController
{
    public function __construct(
        private UserPersonalAwardsRepository $userPersonalAwardsRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserInnovativeEducationRepository $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository $userSocialActivitiesRepository
    )
    {
    }

    #[Route('/account/freezed', name: 'app_account_freezed', methods: ['POST'])]
    public function index(UserInterface $userStorage, #[MapRequestPayload] AccountFreezedDto $dto): JsonResponse
    {
        $stage = $dto->stage;
        $id = $dto->id;
        if ($stage == 'userAwards') {
            $award = $this->userPersonalAwardsRepository->find($id);
            $award->setStatus('freezed');
            $this->userPersonalAwardsRepository->save($award);
        }
        elseif ($stage=='userResearch') {
            $award = $this->userResearchActivitiesListRepository->find($id);
            $award->setStatus('freezed');
            $this->userResearchActivitiesListRepository->save($award);
        }
        elseif ($stage == 'userInnovative') {
            $award = $this->userInnovativeEducationRepository->find($id);
            $award->setStatus('freezed');
            $this->userInnovativeEducationRepository->save($award);
        }
        elseif ($stage == 'userSocial') {
            $award = $this->userSocialActivitiesRepository->find($id);
            $award->setStatus('freezed');
            $this->userSocialActivitiesRepository->save($award);
        }
        else{
            return $this->json(['Такой награды не существует!']);
        }

        return $this->json([
            'Success'
        ]);
    }
}
