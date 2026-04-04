<?php

namespace App\Controller;

use App\Dto\UserAccount\UserAwardsGetDto;
use App\Dto\UserAccount\UserResearchGetDto;
use App\Dto\UserInfoGetDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\ExpertAdjustmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserAccountController extends AbstractController
{
    public function __construct(
        private readonly UserRepository                       $userRepository,
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly TeacherRepository                    $teacherRepository,
        private readonly TeacherAnswerRepository              $teacherAnswerRepository,
        private readonly ExpertAdjustmentRepository           $expertAdjustmentRepository
    )
    {
    }

    #[Route('api/user/account/{id}', name: 'app_user_account', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $teacher = $this->teacherRepository->find($id);
        if ($teacher) {
            $firstOrg = $teacher->getTeacherOrganizations()->first();
            $info = new UserInfoGetDto(
                $teacher->getId(),
                (string)$teacher,
                $firstOrg ? $firstOrg->getInstitute()->getName() : 'N/A',
                $teacher->getPosition() ? $teacher->getPosition()->getName() : 'N/A',
                (string)$firstOrg?->getRegular(),
                $teacher->getEmail()
            );

            $teacherAnswers = $this->teacherAnswerRepository->findBy(['teacher' => $teacher]);
            $awards = [];
            foreach ($teacherAnswers as $answer) {
                $awards[] = new UserAwardsGetDto(
                    $answer->getId(),
                    $answer->getSubtitle()->getTitle()->getName() . ': ' . $answer->getSubtitle()->getName(),
                    $answer->getLink() ?? '',
                    (string)$answer->getSubtitle()->getTitle()->getStage()->getId(),
                    $answer->isActive() ? 'active' : 'freeze'
                );
            }

            return $this->json([
                'userInfo' => $info,
                'userAwards' => $awards,
                'userResearch' => [],
                'userInnovative' => [],
                'userSocial' => [],
                'expertAdjustments' => array_map(fn($a) => [
                    'id' => $a->getId(),
                    'points' => $a->getPoints(),
                    'reason' => $a->getReason(),
                    'expertName' => $a->getExpert()->getJobTitle(),
                    'createdAt' => $a->getCreatedAt()->format('Y-m-d'),
                    'isActive' => $a->isActive()
                ], $this->expertAdjustmentRepository->findBy(['targetTeacher' => $teacher, 'isActive' => true]))
            ]);
        }

        $user = $this->userRepository->find($id);
        if ($user) {
            $userInfo = $this->userInfoRepository->findOneBy(['user' => $user]);
            if ($userInfo == null) {
                return $this->json('Вы не заполинили "Личные данные"');
            }

            $info = new UserInfoGetDto(
                $userInfo->getId(),
                $userInfo->getName(),
                $userInfo->getInstitutions()->getName(),
                $userInfo->getPosition()->getName(),
                (string)$userInfo->getRegular(),
                $userInfo->getEmail());

            // Check for expert adjustments if this user has a teacher record with same ID
            $teacherForAdj = $this->teacherRepository->find($user->getId());

            return $this->json([
                'userInfo' => $info,
                'userAwards' => [],
                'userResearch' => [],
                'userInnovative' => [],
                'userSocial' => [],
                'expertAdjustments' => $teacherForAdj ? array_map(fn($a) => [
                    'id' => $a->getId(),
                    'points' => $a->getPoints(),
                    'reason' => $a->getReason(),
                    'expertName' => $a->getExpert()->getJobTitle(),
                    'createdAt' => $a->getCreatedAt()->format('Y-m-d'),
                    'isActive' => $a->isActive()
                ], $this->expertAdjustmentRepository->findBy(['targetTeacher' => $teacherForAdj, 'isActive' => true])) : []
            ]);
        }

        return $this->json('Пользователь или преподаватель не найден', 404);
    }
}
