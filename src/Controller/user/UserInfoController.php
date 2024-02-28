<?php

namespace App\Controller\user;


use App\Dto\UserInfoDto;
use App\Entity\UserInfo;
use App\Repository\InstitutionsRepository;
use App\Repository\PositionsRepository;
use App\Repository\UserInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserInfoController extends AbstractController
{
    public function __construct(
        private InstitutionsRepository $institutionsRepository,
        private PositionsRepository    $positionsRepository,
        private UserInfoRepository     $userInfoRepository
    )
    {
    }


    #[Route('/info', name: 'app_user_info')]
    public function user_form(): JsonResponse
    {
        return $this->json([
            'institutes' => $this->institutionsRepository->findAll(),
            'position' => $this->positionsRepository->findAll()
        ]);
    }

    #[Route('/info/add', name: 'app_user_form', methods: ['POST'])]
    public function user_form_save(UserInterface $user, #[MapRequestPayload] UserInfoDto $dto): JsonResponse
    {
        $id = $user->getUserIdentifier();
        $userInfo = new UserInfo();
        $userInfo->setName($dto->name);
        $userInfo->setUserId($id);
        $userInfo->setInstitut($dto->institut);
        $userInfo->setPosition($dto->position);
        $userInfo->setRegular($dto->regular);
        $userInfo->setEmail($dto->email);
        $this->userInfoRepository->save($userInfo);
        return $this->json([
            'name' => $dto->name
        ]);
    }
}
