<?php

namespace App\Controller\user;

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
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFreezedAwardsController extends AbstractController
{
    public function __construct(
        private UserRepository                                $userRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository,
    )
    {
    }

    #[Route('/freezed/awards', name: 'app_user_freezed_awards')]
    public function index(UserInterface $userStorage): JsonResponse
    {
        $user = $this->userRepository->find($userStorage->getUserIdentifier());
        $userAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user, 'status' => 'freezed']);
        $userResearch = $this->userResearchActivitiesListRepository->findBy(['user' => $user, 'status' => 'freezed']);
        $userInnovative = $this->userInnovativeEducationRepository->findBy(['user' => $user, 'status' => 'freezed']);
        $userSocial = $this->userSocialActivitiesRepository->findBy(['user' => $user, 'status' => 'freezed']);


        $awards = [];
        foreach ($userAwards as $userAward) {
            $awards[] = new UserAwardsGetDto(
                $userAward->getId(),
                $userAward->getSubtitle()->getTitle()->getName() . ': ' . $userAward->getSubtitle()->getName(),
                $userAward->getLink(),
                "userAwards",
                $userAward->getStatus()
            );
        }
//        User Awards

        $research = [];
        foreach ($userResearch as $item) {
            $research[] = new UserResearchGetDto(
                $item->getId(),
                $item->getSubtitle()->getCategory()->getName() . ': ' . $item->getSubtitle()->getName(),
                $item->getLink(),
                "userResearch",
                $item->getStatus()
            );
        }

        $innovative = [];
        foreach ($userInnovative as $item) {
            $innovative[] = new UserResearchGetDto(
                $item->getId(),
                $item->getInnovativeEducationSubtitle()->getTitle()->getName() . ': ' . $item->getInnovativeEducationSubtitle()->getName(),
                $item->getLink(),
                "userInnovative",
                $item->getStatus()
            );
        }

        $social = [];
        foreach ($userSocial as $item) {
            $social[] = new UserResearchGetDto(
                $item->getId(),
                $item->getSocialActivitiesSubtitle()->getTitle()->getName() . ': ' . $item->getSocialActivitiesSubtitle()->getName(),
                $item->getLink(),
                "userSocial",
                $item->getStatus()
            );
        }


        return $this->json([
            'userAwards' => $awards,
            'userResearch' => $research,
            'userInnovative' => $innovative,
            'userSocial' => $social]);
    }
}
