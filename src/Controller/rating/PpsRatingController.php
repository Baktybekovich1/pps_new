<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\UsersDto;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
use App\Repository\InstituteRepository;
use App\Repository\InstituteAnswerRepository;
use App\Service\Organization\OrganizationPpsService;
use App\Service\UserPointsCountService;
use App\Repository\YearsRepository;
use App\Repository\ExpertAdjustmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Rating')]
class PpsRatingController extends AbstractController
{
    public function __construct(
        private UserPointsCountService               $userPointsCountService,
        private readonly OrganizationPpsService      $organizationPpsService,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
        private readonly TeacherRepository            $teacherRepository,
        private readonly ExpertAdjustmentRepository    $expertAdjustmentRepository,
        private readonly YearsRepository             $yearsRepository
    )
    {
    }

    /**
     * Определяет год по query-параметру ?yearId, либо берёт текущий
     */
    private function resolveYear(Request $request): ?\App\Entity\Years
    {
        $yearId = $request->query->get('yearId');
        if ($yearId) {
            return $this->yearsRepository->find((int)$yearId);
        }
        return $this->yearsRepository->findCurrentYear();
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
    public function institutes_list(
        Request $request,
        InstituteRepository $instituteRepository,
        InstituteAnswerRepository $instituteAnswerRepository,
        \App\Repository\TeacherAnswerRepository $teacherAnswerRepository
    ): JsonResponse
    {
        $year = $this->resolveYear($request);
        $institutes = $instituteRepository->findAll();
        $result = [];
        foreach ($institutes as $institute) {
            $basePoints = $instituteAnswerRepository->getInstitutePoints($institute, $year);
            $teacherPoints = $teacherAnswerRepository->getStaffTeacherPointsForInstitute($institute, $year);
            $expertPoints = $this->expertAdjustmentRepository->getInstituteAdjustedPoints($institute->getId());
            $points = $basePoints + $teacherPoints + $expertPoints;
            $result[] = [
                'id'           => $institute->getId(),
                'name'         => $institute->getName(),
                'organization' => $institute->getOrganization() ? $institute->getOrganization()->getName() : 'N/A',
                'expertPoints' => $expertPoints,
                'points'       => $points,
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
        Request $request,
        InstituteRepository $instituteRepository,
        InstituteAnswerRepository $instituteAnswerRepository,
        \App\Repository\TeacherAnswerRepository $teacherAnswerRepository
    ): JsonResponse
    {
        $year = $this->resolveYear($request);
        $institutes = $instituteRepository->findByOrganization($orgId);
        $result = [];
        foreach ($institutes as $institute) {
            $basePoints = $instituteAnswerRepository->getInstitutePoints($institute, $year);
            $teacherPoints = $teacherAnswerRepository->getStaffTeacherPointsForInstitute($institute, $year);
            $expertPoints = $this->expertAdjustmentRepository->getInstituteAdjustedPoints($institute->getId());
            $points = $basePoints + $teacherPoints + $expertPoints;
            $result[] = [
                'id'           => $institute->getId(),
                'name'         => $institute->getName(),
                'reduction'    => $institute->getReduction(),
                'expertPoints' => $expertPoints,
                'points'       => $points,
                'teacherTotal' => $institute->getTeacherTotal()
            ];
        }

        usort($result, function($a, $b) {
            return $b['points'] <=> $a['points'];
        });

        return $this->json(['institutes' => $result]);
    }

}
