<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\InstitutRatingDto;
use App\Repository\InstitutionsRepository;
use App\Repository\PositionRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ComtehnoRatingController extends AbstractController
{
    public function __construct(
        private readonly UserRepository                       $userRepository,
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly InstitutionsRepository               $institutionsRepository,
        private readonly PositionRepository                   $positionsRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }

    #[Route('comtehno/departments', name: 'app_rating_comtehno_departments')]
    public function index(): JsonResponse
    {
        $institutions = $this->institutionsRepository->findBy(['university' => 'Комтехно']);


        $institutionsJson = [];
        $instSum = 0;
        foreach ($institutions as $institution) {
            $userInfos = $this->userInfoRepository->findBy(['institutions' => $institution]);
            $coll = 0;
            foreach ($userInfos as $userInfo) {
                $user = $userInfo->getUser();
                $sum = $this->getBigPoints($user);
                $instSum = $sum;
                $coll++;
            }
            if ($coll == 0) {
                continue;
            }
            $institutionsJson[] =
                new InstitutRatingDto(
                    $institution->getId(),
                    $institution->getName(),
                    $instSum / $coll,
                    $instSum
                );
        }
        return $this->json(["institutions" => $institutionsJson]);
    }

    public function getBigPoints($user)
    {
        $activity = $this->userResearchActivitiesListRepository->findBy(['user' => $user, 'status' => 'active']);
        $activyCall = $this->getPoints($activity);
        $personalAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user, 'status' => 'active']);
        $upac = $this->getPoints($personalAwards);
        $educations = $this->userInnovativeEducationRepository->findBy(['user' => $user, 'status' => 'active']);
        $eduCall = $this->getPoints($educations);
        $socials = $this->userSocialActivitiesRepository->findBy(['user' => $user, 'status' => 'active']);
        $socialCall = $this->getPoints($socials);
        $offence = $this->userOffenceRepository->findBy(['user' => $user]);
        $sum = $activyCall + $upac + $eduCall + $socialCall;
        foreach ($offence as $value) {
            $sum -= $value->getOffenceList()->getPoints() * $value->getQuantity();
        }

        return $sum;

    }

    public function getPoints($objects)
    {
        $coll = 0;
        foreach ($objects as $object) {
            $coll += $object->getPoints();
        }
        return $coll;

    }

}
