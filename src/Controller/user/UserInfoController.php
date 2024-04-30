<?php

namespace App\Controller\user;


use App\Dto\UserInfoDto;
use App\Dto\UserInfoGetDto;
use App\Entity\UserInfo;
use App\Repository\InstitutionsRepository;
use App\Repository\PositionRepository;
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
        private readonly InstitutionsRepository               $institutionsRepository,
        private readonly PositionRepository                  $positionsRepository,
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly UserRepository                       $userRepository,
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository
    )
    {
    }

    #[Route('/name', name: 'app_user_name')]
    public function user_name(UserInterface $user): JsonResponse
    {
        $user = $this->userRepository->find($user->getUserIdentifier());
        $userInfo = $this->userInfoRepository->findOneBy(['user' => $user]);
        $dto = new UserInfoGetDto(
            $userInfo->getId(),
            $userInfo->getName(),
            $userInfo->getInstitutions()->getName(),
            $userInfo->getPosition(),
            $userInfo->getRegular(),
            $userInfo->getEmail()
        );

        return $this->json([
            'user' => $dto
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
            'id' => $this->userInfoRepository->findOneBy(['user' => $this->userRepository->find($request->get('id'))])
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


    #[Route('/id', name: 'app_user_id')]
    public function id(UserInterface $userInterface): JsonResponse
    {
        $user = $this->userRepository->find($userInterface->getUserIdentifier());
        return $this->json([$user->getId()]);
    }
}
