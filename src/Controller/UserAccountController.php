<?php

namespace App\Controller;

use App\Dto\UserAccount\UserAwardsGetDto;
use App\Dto\UserAccount\UserResearchGetDto;
use App\Dto\UserInfoGetDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserAccountController extends AbstractController
{
    public function __construct(
        private readonly UserRepository                       $userRepository,
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
//        private PersonalAwardsSubtitleRepository      $personalAwardsSubtitleRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
//        private ResearchActivitiesSubtitleRepository  $researchActivitiesSubtitleRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
//        private InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository,
//        private SocialActivitiesSubtitleRepository    $socialActivitiesSubtitleRepository,
//        private PersonalAwardsRepository              $personalAwardsRepository,
//        private ResearchActivitiesListRepository      $researchActivitiesListRepository,
//        private InnovativeEducationListRepository     $innovativeEducationListRepository,
//        private SocialActivitiesListRepository        $socialActivitiesListRepository
    )
    {
    }

    #[Route('api/user/account/{id}', name: 'app_user_account')]
    public function index(Request $request): JsonResponse
    {
        $user = $this->userRepository->find($request->get('id'));
        $userInfo = $this->userInfoRepository->findOneBy(['user' => $user]);
        $userAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user]);
        $userResearch = $this->userResearchActivitiesListRepository->findBy(['user' => $user]);
        $userInnovative = $this->userInnovativeEducationRepository->findBy(['user' => $user]);
        $userSocial = $this->userSocialActivitiesRepository->findBy(['user' => $user]);
        if ($userInfo != null){
            $info = new UserInfoGetDto(
                $userInfo->getId(),
                $userInfo->getName(),
                $userInfo->getInstitutions()->getName(),
                $userInfo->getPosition(),
                $userInfo->getRegular(),
                $userInfo->getEmail());
            // User Info

            $awards = [];
            foreach ($userAwards as $userAward) {
                $awards[] = new UserAwardsGetDto(
                    $userAward->getId(),
                    $userAward->getSubtitle()->getTitle()->getName() . ': ' . $userAward->getSubtitle()->getName(),
                    $userAward->getLink(),
                    "award",
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
                    "research",
                    $item->getStatus()
                );
            }

            $innovative = [];
            foreach ($userInnovative as $item) {
                $innovative[] = new UserResearchGetDto(
                    $item->getId(),
                    $item->getInnovativeEducationSubtitle()->getTitle()->getName() . ': ' . $item->getInnovativeEducationSubtitle()->getName(),
                    $item->getLink(),
                    "innovative",
                    $item->getStatus()
                );
            }

            $social = [];
            foreach ($userSocial as $item) {
                $social[] = new UserResearchGetDto(
                    $item->getId(),
                    $item->getSocialActivitiesSubtitle()->getTitle()->getName() . ': ' . $item->getSocialActivitiesSubtitle()->getName(),
                    $item->getLink(),
                    "social",
                    $item->getStatus()
                );
            }
        }
        else{
            return $this->json('Вы не заполинили "Личные данные"');
        }

        return $this->json([
            'userInfo' => $info,
            'userAwards' => $awards,
            'userResearch' => $research,
            'userInnovative' => $innovative,
            'userSocial' => $social
        ]);
    }
}
