<?php

namespace App\Controller\user;

use App\Dto\UserAccount\UserAccountAwardsEditSetDto;
use App\Exception\NotAccessToAwardExistsException;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAccountAwardsEditController extends AbstractController
{
    public function __construct(
        private UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private UserSocialActivitiesRepository       $userSocialActivitiesRepository,
        private UserRepository                       $userRepository
    )
    {
    }


    #[Route('/account/award/edit', name: 'app_user_account_edit', methods: ['PUT'])]
    public function award(#[MapRequestPayload] UserAccountAwardsEditSetDto $dto, UserInterface $userInterface): JsonResponse
    {
        $user = $this->userRepository->find($userInterface->getUserIdentifier());

        $test = True;
        if ($dto->stage == "award") {
            $test = $this->edit($this->userPersonalAwardsRepository, $dto->id, $dto->link, $user);
        } elseif ($dto->stage == "research") {
            $test = $this->edit($this->userResearchActivitiesListRepository, $dto->id, $dto->link, $user);
        } elseif ($dto->stage == "innovative") {
            $test = $this->edit($this->userInnovativeEducationRepository, $dto->id, $dto->link, $user);
        } elseif ($dto->stage == "social") {
            $test = $this->edit($this->userSocialActivitiesRepository, $dto->id, $dto->link, $user);
        }
        if (!$test) {
            throw new NotAccessToAwardExistsException();
        }

        return $this->json([
            "Success"
        ]);
    }

    public function edit($repository, $id, $text, $user): bool
    {
        $award = $repository->find($id);
        if ($award->getUser() == $user) {
            $award->setLink($text);
            $repository->save($award);
            return True;
        }
        else {
            return False;
        }
    }


}
