<?php

namespace App\Controller\user;

use App\Repository\UserPersonalAwardsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UserAccountAwardsEditController extends AbstractController
{
//    private UserPersonalAwardsRepository $userPersonalAwardsRepository,
//    private
//
//    #[Route('/user/account/awards/edit', name: 'app_user_account_awards_edit')]
//    public function index(#[MapRequestPayload] ): JsonResponse
//    {
//        foreach ($dto->idBag as $id) {
//            $award = $this->userPersonalAwardsRepository->find($id);
//            $award->setStatus('active');
//            $this->userPersonalAwardsRepository->save($award);
//        }
//        return $this->json([
//            ,
//        ]);
//    }
}
