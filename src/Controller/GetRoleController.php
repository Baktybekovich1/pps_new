<?php

namespace App\Controller;

use App\Dto\UserInfoGetDto;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GetRoleController extends AbstractController
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

    #[Route('/api/get/role', name: 'app_get_role')]
    public function index(UserInterface $userStorage): JsonResponse
    {
        if ($userStorage->getUserIdentifier() != null) {
            $user = $this->userRepository->find($userStorage->getUserIdentifier());
            $role = $user->getRoles();
            if (count($role) > 1) {
                $get = 'admin';
            } else {
                $get = 'user';
            }
            return $this->json([
                'role' => $get
            ]);
        } else {
            return $this->json([
                'role' => 'visitor'
            ]);
        }
    }


    #[Route('api/user/info', name: 'app_user_info')]
    public function user_info(): JsonResponse
    {
        $positions = $this->positionsRepository->findAll();
        $institutes = $this->institutionsRepository->findAll();
        $university = ["МУИТ", "КИТЭ", "Комтехно"];
        $post = [];
//        foreach ($institutes as $institute) {
//            $inst[] = $institute->getName();
//        }
        foreach ($positions as $position) {
            $post[] = $position->getName();
        }

        return $this->json([
            'university' => $university,
            'institutes' => $institutes,
            'position' => $post
        ]);
    }

    #[Route('i/am/akai/and/i/want/delete/all/users/and/awards', name: 'app_ddd')]
    public function fatal_delete_all_users_and_awards(): JsonResponse
    {
        $info = $this->userInfoRepository->findAll();
        $offence = $this->userOffenceRepository->findAll();
        $innovative = $this->userInnovativeEducationRepository->findAll();
        $awards = $this->userPersonalAwardsRepository->findAll();
        $research = $this->userResearchActivitiesListRepository->findAll();
        $social = $this->userSocialActivitiesRepository->findAll();
        $users = $this->userRepository->findAll();

        $this->delete($awards, $this->userPersonalAwardsRepository);
        $this->delete($innovative, $this->userInnovativeEducationRepository);
        $this->delete($offence, $this->userOffenceRepository);
        $this->delete($research, $this->userResearchActivitiesListRepository);
        $this->delete($social, $this->userSocialActivitiesRepository);
        $this->delete($info, $this->userInfoRepository);
        $this->delete($users, $this->userRepository);
        return $this->json(["SUCCESS"]);
    }

    public
    function delete($awards, $repository)
    {
        foreach ($awards as $award) {
            $repository->remove($award);
        }
    }

    #[Route('api/user/account/award/get/{id}', name: 'app_user_account_award_get')]
    public function award(Request $request): JsonResponse
    {
        $award = $this->userPersonalAwardsRepository->find($request->get('id'));
        $name = $award->getSubtitle()->getTitle()->getName() . ': ' . $award->getSubtitle()->getName();
        return $this->json([
            "name" => $name,
            "link" => $award->getLink(),
            "stage" => "award"
        ]);
    }

}
