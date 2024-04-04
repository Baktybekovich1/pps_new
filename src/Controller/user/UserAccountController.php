<?php

namespace App\Controller\user;

use App\Dto\UserAccount\UserAwardsGetDto;
use App\Dto\UserInfoGetDto;
use App\Entity\InnovativeEducationSubtitle;
use App\Entity\PersonalAwardsSubtitle;
use App\Entity\SocialActivitiesSubtitle;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\InnovativeEducationSubtitleRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\SocialActivitiesListRepository;
use App\Repository\SocialActivitiesSubtitleRepository;
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
        private UserRepository               $userRepository,
        private UserInfoRepository           $userInfoRepository,
        private UserPersonalAwardsRepository $userPersonalAwardsRepository,
//        private PersonalAwardsSubtitleRepository      $personalAwardsSubtitleRepository,
//        private UserResearchActivitiesListRepository  $userResearchActivitiesListRepository,
//        private ResearchActivitiesSubtitleRepository  $researchActivitiesSubtitleRepository,
//        private UserInnovativeEducationRepository     $userInnovativeEducationRepository,
//        private InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository,
//        private UserSocialActivitiesRepository        $userSocialActivitiesRepository,
//        private SocialActivitiesSubtitleRepository    $socialActivitiesSubtitleRepository,
//        private PersonalAwardsRepository              $personalAwardsRepository,
//        private ResearchActivitiesListRepository      $researchActivitiesListRepository,
//        private InnovativeEducationListRepository     $innovativeEducationListRepository,
//        private SocialActivitiesListRepository        $socialActivitiesListRepository
    )
    {
    }

    #[Route('/account/{id}', name: 'app_user_account')]
    public function index(Request $request): JsonResponse
    {
        $user = $this->userRepository->find($request->get('id'));
        $userInfo = $this->userInfoRepository->findOneBy(['user' => $user]);
        $userAwards = $this->userPersonalAwardsRepository->findOneBy(['user' => $user]);
        $info = new UserInfoGetDto(
            $userInfo->getId(),
            $userInfo->getName(),
            $userInfo->getInstitutions()->getName(),
            $userInfo->getPosition(),
            $userInfo->getRegular(),
            $userInfo->getEmail());
        dd($userAwards);
        $awards = [];
        foreach ($userAwards as $userAward) {
//            $awards[] = new UserAwardsGetDto(
//
//            )
        }
        return $this->json([
            'userInfo' => $userAwards->getSubtitle()->getName()
        ]);
    }
}
