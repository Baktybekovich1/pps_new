<?php

namespace App\Controller\user;

use App\Dto\AdminFreezeSetAwardDto;
use App\Repository\UserPersonalAwardsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UserAccountAwardsDeleteController extends AbstractController
{
    private UserPersonalAwardsRepository $userPersonalAwardsRepository;

    #[Route('/account/award/delete', name: 'app_user_account_award_delete' ,methods: ['DELETE'])]
    public function award(#[MapRequestPayload] AdminFreezeSetAwardDto $dto): JsonResponse
    {
        foreach ($dto->idBag as $id) {
            $award = $this->userPersonalAwardsRepository->find($id);
            $this->userPersonalAwardsRepository->remove($award);
        }

        return $this->json([
            'Success'
        ]);
    }
}
