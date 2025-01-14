<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\QuestionPPSRatingDto;
use App\Repository\InnovativeEducationListRepository;
use App\Repository\InnovativeEducationSubtitleRepository;
use App\Repository\PersonalAwardsRepository;
use App\Repository\PersonalAwardsSubtitleRepository;
use App\Repository\ResearchActivitiesListRepository;
use App\Repository\ResearchActivitiesSubtitleRepository;
use App\Repository\SocialActivitiesListRepository;
use App\Repository\SocialActivitiesSubtitleRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
#[OA\Tag(name: 'Rating')]
class QuestionComtehnoRatingController extends AbstractController
{


    public function __construct(
        private readonly UserInfoRepository                    $userInfoRepository,
        private readonly UserPersonalAwardsRepository          $userPersonalAwardsRepository,
        private readonly UserInnovativeEducationRepository     $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository        $userSocialActivitiesRepository,
        private readonly PersonalAwardsRepository              $personalAwardsRepository,
        private readonly PersonalAwardsSubtitleRepository      $personalAwardsSubtitleRepository,
        private readonly ResearchActivitiesListRepository      $researchActivitiesListRepository,
        private readonly UserResearchActivitiesListRepository  $userResearchActivitiesListRepository,
        private readonly ResearchActivitiesSubtitleRepository  $researchActivitiesSubtitleRepository,
        private readonly InnovativeEducationListRepository     $innovativeEducationListRepository,
        private readonly InnovativeEducationSubtitleRepository $innovativeEducationSubtitleRepository,
        private readonly SocialActivitiesListRepository        $socialActivitiesListRepository,
        private readonly SocialActivitiesSubtitleRepository    $socialActivitiesSubtitleRepository
    )
    {
    }

    #[Route('/question/comtehno/awards/{titleId}/{subId}', name: 'app_question_comtehno_awards',methods: ['GET'])]
    public function getPps(Request $request): JsonResponse
    {
        if ($request->get('titleId') != null) {
            $title = $this->personalAwardsRepository->find($request->get('titleId'));
            $userInfos = $this->userInfoRepository->findAll();
            $pps = [];

            foreach ($userInfos as $userInfo) {
                $points = 0;
                $user = $userInfo->getUser();
                if ($userInfo->getInstitutions()->getUniversity() != 'Комтехно') {
                    continue;
                }

                $personalAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user, 'status' => 'active']);
                foreach ($personalAwards as $personalAward) {
                    if ($personalAward->getSubtitle()->getTitle() !== $title) {
                        continue;
                    }
                    if ($request->get('subId') != null) {
                        $sub = $this->personalAwardsSubtitleRepository->find($request->get('subId'));
                        if ($personalAward->getSubtitle() !== $sub) {
                            continue;
                        }
                    }
                    $points += $personalAward->getSubtitle()->getPoints();
                }
                if ($points == 0) {
                    continue;
                }
                $pps[] = new QuestionPPSRatingDto(
                    $user->getId(),
                    $userInfo->getName(),
                    $userInfo->getInstitutions()->getName(),
                    $points
                );
            }
        }
        return $this->json($pps);

    }

    #[Route('/question/comtehno/research/{titleId}/{subId}', name: 'app_question_comtehno_research',methods: ['GET'])]
    public function getResearchPps(Request $request): JsonResponse
    {
        if ($request->get('titleId') != null) {
            $title = $this->researchActivitiesListRepository->find($request->get('titleId'));
            $userInfos = $this->userInfoRepository->findAll();
            $pps = [];

            foreach ($userInfos as $userInfo) {
                $points = 0;
                $user = $userInfo->getUser();
                if ($userInfo->getInstitutions()->getUniversity() != 'Комтехно') {
                    continue;
                }

                $personalAwards = $this->userResearchActivitiesListRepository->findBy(['user' => $user, 'status' => 'active']);
                foreach ($personalAwards as $personalAward) {
                    if ($personalAward->getSubtitle()->getCategory() !== $title) {
                        continue;
                    }
                    if ($request->get('subId') != null) {
                        $sub = $this->researchActivitiesSubtitleRepository->find($request->get('subId'));
                        if ($personalAward->getSubtitle() !== $sub) {
                            continue;
                        }
                    }
                    $points += $personalAward->getSubtitle()->getPoints();
                }
                if ($points == 0) {
                    continue;
                }
                $pps[] = new QuestionPPSRatingDto(
                    $user->getId(),
                    $userInfo->getName(),
                    $userInfo->getInstitutions()->getName(),
                    $points
                );
            }
        }
        return $this->json($pps);

    }


    #[Route('/question/comtehno/innovative/{titleId}/{subId}', name: 'app_question_comtehno_innovative',methods: ['GET'])]
    public function getInnovativePps(Request $request): JsonResponse
    {
        if ($request->get('titleId') != null) {
            $title = $this->innovativeEducationListRepository->find($request->get('titleId'));
            $userInfos = $this->userInfoRepository->findAll();
            $pps = [];

            foreach ($userInfos as $userInfo) {
                $points = 0;
                $user = $userInfo->getUser();
                if ($userInfo->getInstitutions()->getUniversity() != 'Комтехно') {
                    continue;
                }

                $personalAwards = $this->userInnovativeEducationRepository->findBy(['user' => $user, 'status' => 'active']);
                foreach ($personalAwards as $personalAward) {
                    if ($personalAward->getInnovativeEducationSubtitle()->getTitle() !== $title) {
                        continue;
                    }
                    if ($request->get('subId') != null) {
                        $sub = $this->innovativeEducationSubtitleRepository->find($request->get('subId'));
                        if ($personalAward->getInnovativeEducationSubtitle() !== $sub) {
                            continue;
                        }
                    }
                    $points += $personalAward->getInnovativeEducationSubtitle()->getPoints();
                }
                if ($points == 0) {
                    continue;
                }
                $pps[] = new QuestionPPSRatingDto(
                    $user->getId(),
                    $userInfo->getName(),
                    $userInfo->getInstitutions()->getName(),
                    $points
                );
            }
        }
        return $this->json($pps);

    }


    #[Route('/question/comtehno/social/{titleId}/{subId}', name: 'app_question_comtehno_social',methods: ['GET'])]
    public function getSocialPps(Request $request): JsonResponse
    {
        if ($request->get('titleId') != null) {
            $title = $this->socialActivitiesListRepository->find($request->get('titleId'));
            $userInfos = $this->userInfoRepository->findAll();
            $pps = [];

            foreach ($userInfos as $userInfo) {
                $points = 0;
                $user = $userInfo->getUser();
                if ($userInfo->getInstitutions()->getUniversity() != 'Комтехно') {
                    continue;
                }

                $personalAwards = $this->userSocialActivitiesRepository->findBy(['user' => $user, 'status' => 'active']);
                foreach ($personalAwards as $personalAward) {
                    if ($personalAward->getSocialActivitiesSubtitle()->getTitle() !== $title) {
                        continue;
                    }
                    if ($request->get('subId') != null) {
                        $sub = $this->socialActivitiesSubtitleRepository->find($request->get('subId'));
                        if ($personalAward->getSocialActivitiesSubtitle() !== $sub) {
                            continue;
                        }
                    }
                    $points += $personalAward->getSocialActivitiesSubtitle()->getPoints();
                }
                if ($points == 0) {
                    continue;
                }
                $pps[] = new QuestionPPSRatingDto(
                    $user->getId(),
                    $userInfo->getName(),
                    $userInfo->getInstitutions()->getName(),
                    $points
                );
            }
        }
        return $this->json($pps);

    }

}