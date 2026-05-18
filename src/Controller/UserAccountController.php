<?php

namespace App\Controller;

use App\Dto\UserAccount\UserAwardsGetDto;
use App\Dto\UserAccount\UserResearchGetDto;
use App\Dto\UserInfoGetDto;
use App\Repository\UserRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\YearsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserAccountController extends AbstractController
{
    public function __construct(
        private readonly UserRepository                       $userRepository,
        private readonly TeacherRepository                    $teacherRepository,
        private readonly TeacherAnswerRepository              $teacherAnswerRepository,
        private readonly ExpertAdjustmentRepository           $expertAdjustmentRepository,
        private readonly YearsRepository                      $yearsRepository
    )
    {
    }

    #[Route('api/user/account/{id}', name: 'app_user_account', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $yearId = $request->query->getInt('yearId') ?: null;
        $selectedYear = $yearId ? $this->yearsRepository->find($yearId) : $this->yearsRepository->findCurrentYear();
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

            $teacherAnswers = $this->teacherAnswerRepository->findByTeacherAndYear($teacher, $selectedYear);
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
                ], $this->expertAdjustmentRepository->findActiveByTeacherAndYear($teacher, $selectedYear))
            ]);
        }

        $user = $this->userRepository->find($id);
        if ($user) {
            // Find teacher by email
            $teacher = $this->teacherRepository->findOneBy(['email' => $user->getUsername()]);
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

                return $this->json([
                    'userInfo' => $info,
                    'userAwards' => [], // could fill or redirect
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
                    ], $this->expertAdjustmentRepository->findActiveByTeacherAndYear($teacher, $selectedYear))
                ]);
            }
            return $this->json('Профиль преподавателя не найден для этого пользователя');
        }

        return $this->json('Пользователь или преподаватель не найден', 404);
    }
}
