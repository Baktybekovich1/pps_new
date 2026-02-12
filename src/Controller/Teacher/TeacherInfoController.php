<?php

declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Dto\UserInfoGetDto;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TeacherInfoController extends AbstractController
{
    public function __construct(
        private readonly TeacherRepository $teacherRepo,
    )
    {
    }

//    #[Route('/name', name: 'app_user_name', methods: ['GET'])]
//    public function user_name(UserInterface $user): JsonResponse
//    {
//        $teacher = $this->teacherRepo->find($user->getUserIdentifier());
//        if ($teacher === null) {
//            return $this->json([null]);
//        } else {
//            $dto = new UserInfoGetDto(
//                $teacher->getId(),
//                $teacher->getName(),
//                $teacher->,
//                $userInfo->getPosition()->getName(),
//                $userInfo->getRegular(),
//                $userInfo->getEmail()
//            );
//        }
//
//        return $this->json([
//            'user' => $dto
//        ]);
//    }
    #[Route('/id', name: 'app_user_id', methods: ['GET'])]
    public function id(UserInterface $userInterface): JsonResponse
    {
        $teacher = $this->teacherRepo->find($userInterface->getUserIdentifier());
        return $this->json(["id" => $teacher->getId()]);
    }
}
