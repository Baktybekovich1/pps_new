<?php

namespace App\Controller;

use App\Dto\UserAccount\UserAwardsGetDto;
use App\Dto\UserAccount\UserResearchGetDto;
use App\Dto\UserInfoGetDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserSocialActivitiesRepository;
use App\Repository\UserResearchActivitiesListRepository;
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
        private readonly UserPersonalAwardsRepository         $userPersonalAwardsRepository,
        private readonly UserResearchActivitiesListRepository $userResearchActivitiesListRepository,
        private readonly UserInnovativeEducationRepository    $userInnovativeEducationRepository,
        private readonly UserSocialActivitiesRepository       $userSocialActivitiesRepository,
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

            $userAwards = $this->userPersonalAwardsRepository->findBy(['user' => $user]);
            $userResearch = $this->userResearchActivitiesListRepository->findBy(['user' => $user]);
            $userInnovative = $this->userInnovativeEducationRepository->findBy(['user' => $user]);
            $userSocial = $this->userSocialActivitiesRepository->findBy(['user' => $user]);

            $awards = [];
            foreach ($userAwards as $userAward) {
                $awards[] = new UserAwardsGetDto(
                    $userAward->getId(),
                    $userAward->getSubtitle()->getTitle()->getName() . ': ' . $userAward->getSubtitle()->getName(),
                    $userAward->getLink(),
                    "award",
                    $userAward->getStatus()
                );
            }

            $research = [];
            foreach ($userResearch as $item) {
                $research[] = new UserResearchGetDto(
                    $item->getId(),
                    $item->getSubtitle()->getCategory()->getName() . ': ' . $item->getSubtitle()->getName(),
                    $item->getLink(),
                    "research",
                    $item->getStatus()
                );
            }

            $innovative = [];
            foreach ($userInnovative as $item) {
                $innovative[] = new UserResearchGetDto(
                    $item->getId(),
                    $item->getInnovativeEducationSubtitle()->getTitle()->getName() . ': ' . $item->getInnovativeEducationSubtitle()->getName(),
                    $item->getLink(),
                    "innovative",
                    $item->getStatus()
                );
            }

            $social = [];
            foreach ($userSocial as $item) {
                $social[] = new UserResearchGetDto(
                    $item->getId(),
                    $item->getSocialActivitiesSubtitle()->getTitle()->getName() . ': ' . $item->getSocialActivitiesSubtitle()->getName(),
                    $item->getLink(),
                    "social",
                    $item->getStatus()
                );
            }

            // Find associated teacher for adjustments
            $teacher = $this->teacherRepository->find($user->getId());

            return $this->json([
                'userInfo' => $info,
                'userAwards' => $awards,
                'userResearch' => $research,
                'userInnovative' => $innovative,
                'userSocial' => $social,
                'expertAdjustments' => $teacher ? array_map(fn($a) => [
                    'id' => $a->getId(),
                    'points' => $a->getPoints(),
                    'reason' => $a->getReason(),
                    'expertName' => $a->getExpert()->getJobTitle(),
                    'createdAt' => $a->getCreatedAt()->format('Y-m-d'),
                    'isActive' => $a->isActive()
                ], $this->expertAdjustmentRepository->findBy(['targetTeacher' => $teacher, 'isActive' => true])) : []
            ]);
        }

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

        return $this->json('Пользователь или преподаватель не найден', 404);
    }
}
