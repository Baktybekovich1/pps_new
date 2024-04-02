<?php

namespace App\Controller\user;


use App\Dto\UserGetName;
use App\Dto\UserInfoDto;
use App\Entity\UserInfo;
use App\Repository\InstitutionsRepository;
use App\Repository\PositionsRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserInfoController extends AbstractController
{
    public function __construct(
        private InstitutionsRepository               $institutionsRepository,
        private PositionsRepository                  $positionsRepository,
        private UserInfoRepository                   $userInfoRepository,
        private UserRepository                       $userRepository,
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }

    #[Route('/name', name: 'app_user_name')]
    public function user_name(UserInterface $user): JsonResponse
    {
        $userInfo = $this->userInfoRepository->find($user->getUserIdentifier());

        return $this->json([
            'name' => $userInfo->getName()
        ]);
    }

    #[Route('/info', name: 'app_user_info')]
    public function user_info(): JsonResponse
    {
        return $this->json([
            'institutes' => $this->institutionsRepository->findAll(),
            'position' => $this->positionsRepository->findAll()
        ]);

    }

    #[Route('/us/{id}', name: 'app_user_us')]
    public function us(Request $request): JsonResponse
    {
        return $this->json([
            'id' => $this->userInfoRepository->find($request->get('id'))
        ]);
    }

    #[Route('/info/add', name: 'app_user_form', methods: ['POST'])]
    public function user_form_save(UserInterface $user, #[MapRequestPayload] UserInfoDto $dto): JsonResponse
    {
        $id = $user->getUserIdentifier();
        $userInfo = new UserInfo();
        $userInfo->setName($dto->name);
        $userInfo->setUser($this->userRepository->find($user->getUserIdentifier()));
        $userInfo->setInstitutions($this->institutionsRepository->find($this->institutionsRepository->find($dto->institut)));
        $userInfo->setPosition($dto->position);
        $userInfo->setRegular($dto->regular);
        $userInfo->setEmail($dto->email);
        $this->userInfoRepository->save($userInfo);
        return $this->json([
            'name' => $dto->name
        ]);
    }

    #[Route('/info/list/{id}', name: 'app_user_info_list')]
    public function user_info_list(Request $request): JsonResponse
    {
        $user = $this->userRepository->find($request->get('id'));
        $name = $this->userInfoRepository->findOneBy(['user' => $user]);
        $personalAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user]);
        $researchActivities = $this->userResearchActivitiesListRepository->findBy(['user' => $user]);
        $innovative = $this->userInnovativeEducationRepository->findBy(['user' => $user]);
        $social = $this->userSocialActivitiesRepository->findBy(['user' => $user]);
        $name = $this->userInfoRepository->findOneBy(['user' => $user]);

        return $this->json([
            'name' => $name->getName(),
//            'personalAwards' => $this->userPersonalAwardsRepository->findBy(['user' => $user]),
            'researchActivities' => $this->userResearchActivitiesListRepository->findBy(['user' => $user]),
            'innovative' => $this->userInnovativeEducationRepository->findBy(['user' => $user]),
            'social' => $this->userSocialActivitiesRepository->findBy(['user' => $user])
        ]);

    }
}
