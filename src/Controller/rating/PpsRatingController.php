<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\UsersDto;
use App\Repository\UserInfoRepository;
use App\Repository\UserInnovativeEducationRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\UserPersonalAwardsRepository;
use App\Repository\UserRepository;
use App\Repository\UserResearchActivitiesListRepository;
use App\Repository\UserSocialActivitiesRepository;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
use App\Repository\InstituteRepository;
use App\Repository\InstituteAnswerRepository;
use App\Service\Organization\OrganizationPpsService;
use App\Service\UserPointsCountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Rating')]
class PpsRatingController extends AbstractController
{
    public function __construct(
        private UserInfoRepository                   $userInfoRepository,
        private UserPointsCountService               $userPointsCountService,
        private readonly OrganizationPpsService      $organizationPpsService,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
        private readonly TeacherRepository            $teacherRepository
    )
    {
    }

    #[Route('/pps', name: 'app_pps_rating', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $pps = $this->userPointsCountService->UserPointsCount();

        return $this->json(['pps' => $pps]);
    }
    #[Route('/organization/{orgId}/pps', name: 'app_organization_pps_rating', methods: ['GET'])]
    public function orgPps(Request $request): JsonResponse
    {
        return $this->json($this->organizationPpsService->organizationPps($request->get('orgId')));
    }


    #[Route('/users', name: 'app_pps_users', methods: ['GET'])]
    public function users_list(Request $request): JsonResponse
    {
        $orgId = $request->get('orgId');
        if ($orgId) {
            $teacherOrgs = $this->teacherOrganizationRepository->findBy(['organization' => $orgId]);
            $users = [];
            foreach ($teacherOrgs as $to) {
                $teacher = $to->getTeacher();
                $users[] = new UsersDto($teacher->getId(), (string)$teacher);
            }
        } else {
            $teacherEntities = $this->teacherRepository->findAll();
            $users = [];
            foreach ($teacherEntities as $teacher) {
                $users[] = new UsersDto($teacher->getId(), (string)$teacher);
            }
        }

        return $this->json([
            'users' => $users
        ]);
    }

    #[Route('/institutes', name: 'app_pps_institutes', methods: ['GET'])]
    public function institutes_list(InstituteRepository $instituteRepository, InstituteAnswerRepository $instituteAnswerRepository, \App\Repository\TeacherAnswerRepository $teacherAnswerRepository): JsonResponse
    {
        $institutes = $instituteRepository->findAll();
        $result = [];
        foreach ($institutes as $institute) {
            $basePoints = $instituteAnswerRepository->getInstitutePoints($institute);
            $teacherPoints = $teacherAnswerRepository->getStaffTeacherPointsForInstitute($institute);
            $points = $basePoints + $teacherPoints;
            $result[] = [
                'id' => $institute->getId(),
                'name' => $institute->getName(),
                'organization' => $institute->getOrganization() ? $institute->getOrganization()->getName() : 'N/A',
                'points' => $points,
                'teacherTotal' => $institute->getTeacherTotal()
            ];
        }

        usort($result, function($a, $b) {
            return $b['points'] <=> $a['points'];
        });

        return $this->json(['institutes' => $result]);
    }

    #[Route('/organization/{orgId}/institutes', name: 'app_organization_institutes_rating', methods: ['GET'])]
    public function organization_institutes(
        int $orgId,
        InstituteRepository $instituteRepository,
        InstituteAnswerRepository $instituteAnswerRepository,
        \App\Repository\TeacherAnswerRepository $teacherAnswerRepository
    ): JsonResponse
    {
        $institutes = $instituteRepository->findByOrganization($orgId);
        $result = [];
        foreach ($institutes as $institute) {
            $basePoints = $instituteAnswerRepository->getInstitutePoints($institute);
            $teacherPoints = $teacherAnswerRepository->getStaffTeacherPointsForInstitute($institute);
            $points = $basePoints + $teacherPoints;
            $result[] = [
                'id' => $institute->getId(),
                'name' => $institute->getName(),
                'reduction' => $institute->getReduction(),
                'points' => $points,
                'teacherTotal' => $institute->getTeacherTotal()
            ];
        }

        usort($result, function($a, $b) {
            return $b['points'] <=> $a['points'];
        });

        return $this->json(['institutes' => $result]);
    }

}
