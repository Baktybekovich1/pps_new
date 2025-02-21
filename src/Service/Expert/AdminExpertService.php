<?php

namespace App\Service\Expert;

use App\Entity\Expert;
use App\Repository\ExpertRepository;
use App\Repository\UserRepository;

readonly class AdminExpertService
{
    public function __construct(
        private ExpertRepository $expertRepository, private UserRepository $userRepository
    )
    {
    }

    public function newExpert($dto): bool
    {
        $user = $this->userRepository->find($dto->userId);
        $expert = new Expert();
        $expert->setAccount($user);
        $expert->setjobTitle($dto->jobTitle);
        $this->expertRepository->save($expert);
        $user->setRoles(['ROLE_EXPERT']);
        $this->userRepository->save($user);
        return true;
    }

    public function removeExpert(int $id): bool
    {
        $expert = $this->expertRepository->find($id);
        $this->expertRepository->remove($expert);
        return true;
    }

}