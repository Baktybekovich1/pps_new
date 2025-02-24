<?php

namespace App\Service\Expert;

use App\Entity\Expert;
use App\Entity\User;
use App\Entity\UserExpertPoint;
use App\Repository\UserExpertPointRepository;

class ExpertService
{
    public function __construct(private readonly UserExpertPointRepository $userExpertPointRepository)
    {
    }

    public function addExpertAddPointsToUser(Expert $expert, User $user, int $point): bool
    {
        $userExpertPoint = new UserExpertPoint();
        $userExpertPoint->setExpert($expert);
        $userExpertPoint->setTeacher($user);
        $userExpertPoint->setPoint($point);
        $this->userExpertPointRepository->save($userExpertPoint);
        return true;
    }
}