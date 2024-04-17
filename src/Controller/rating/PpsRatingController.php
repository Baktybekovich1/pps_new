<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\UsersDto;
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

class PpsRatingController extends AbstractController
{
    public function __construct(
        private UserInfoRepository                   $userInfoRepository,
        private UserResearchActivitiesListRepository $userActivitiesListsRepository,
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserRepository                       $userRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository,
        private UserOffenceRepository                $userOffenceRepository
    )
    {
    }

    #[Route('/pps', name: 'app_pps_rating')]
    public function index(): JsonResponse
    {
        $pps = [];
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $info = $this->userInfoRepository->findOneBy(['user' => $user]);
            $activity = $this->userActivitiesListsRepository->findBy(['user' => $user,'status' => 'active']);
            $activyCall = $this->getPoints($activity);
            $personalAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user,'status' => 'active']);
            $upac = $this->getPoints($personalAwards);
            $educations = $this->userInnovativeEducationRepository->findBy(['user' => $user,'status' => 'active']);
            $eduCall = $this->getPoints($educations);
            $socials = $this->userSocialActivitiesRepository->findBy(['user' => $user,'status' => 'active']);
            $socialCall = $this->getPoints($socials);
            $offence = $this->userOffenceRepository->findBy(['user' => $user]);
            $sum = $activyCall + $upac + $eduCall + $socialCall;
            foreach ($offence as $value) {
                $sum -= $value->getOffenceList()->getPoints() * $value->getQuantity();
            }
            if (isset($pps[$user->getId()])) {
                /** @var PpsRatingDto $dto */

                $dto = $pps[$user->getId()];
                $dto->id = $user->getId();
                $dto->researchPoints += $activyCall;
                $dto->awardPoints += $upac;
                $dto->innovativePoints += $eduCall;
                $dto->socialPoints += $socialCall;
                $dto->sum += $sum;

            } else {
                $pps[$user->getId()] = new PpsRatingDto(
                    $user->getId(),
                    $info->getName(),
                    $info->getInstitutions()->getName(),
                    $activyCall,
                    $upac,
                    $eduCall,
                    $socialCall,
                    $sum
                );
            }
        }
        return $this->json([
            'pps' => $pps
        ]);
    }

    #[Route('/users', name: 'app_pps_users')]
    public function users_list(): JsonResponse
    {
        $userInfo = $this->userInfoRepository->findAll();
        $users = [];
        foreach ($userInfo as $value) {
            $item = new UsersDto(
                $value->getUser()->getId(),
                $value->getName()
            );
            $users[] = $item;
        }
        return $this->json([
            'users' => $users
        ]);
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
