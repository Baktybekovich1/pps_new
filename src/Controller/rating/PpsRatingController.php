<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\UsersDto;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
use App\Repository\InstituteRepository;
use App\Repository\InstituteAnswerRepository;
use App\Repository\QuestionSubtitleRepository;
use App\Service\Organization\OrganizationPpsService;
use App\Repository\YearsRepository;
use App\Repository\ExpertAdjustmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Rating')]
class PpsRatingController extends AbstractController
{
    public function __construct(
        private readonly OrganizationPpsService      $organizationPpsService,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
        private readonly TeacherRepository            $teacherRepository,
        private readonly ExpertAdjustmentRepository    $expertAdjustmentRepository,
        private readonly YearsRepository             $yearsRepository,
        private readonly QuestionSubtitleRepository  $questionSubtitleRepository
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
            $year = $this->yearsRepository->find((int)$yearId);
            if (!$year) {
                throw new BadRequestHttpException(sprintf('Invalid yearId: %s', $yearId));
            }
            return $year;
        }
        return $this->yearsRepository->findCurrentYear();
    }

    #[Route('/organization/{orgId}/pps', name: 'app_organization_pps_rating', methods: ['GET'])]
    public function orgPps(Request $request): JsonResponse
    {
        $year = $this->resolveYear($request);

        return $this->json($this->organizationPpsService->organizationPps((int)$request->get('orgId'), $year));
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
            $expertPoints = $this->expertAdjustmentRepository->getInstituteAdjustedPoints($institute->getId(), $year);
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
            $expertPoints = $this->expertAdjustmentRepository->getInstituteAdjustedPoints($institute->getId(), $year);
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

    #[Route('/organization/{orgId}/institutes/{instituteId}/teachers', name: 'app_organization_institute_teachers_rating', methods: ['GET'])]
    public function organization_institute_teachers(
        int $orgId,
        int $instituteId,
        Request $request,
        InstituteRepository $instituteRepository,
        \App\Repository\TeacherAnswerRepository $teacherAnswerRepository
    ): JsonResponse
    {
        $year = $this->resolveYear($request);
        $institute = $instituteRepository->find($instituteId);
        if (!$institute || !$institute->getOrganization() || $institute->getOrganization()->getId() !== $orgId) {
            throw $this->createNotFoundException('Institute not found for organization');
        }

        $teacherRows = $teacherAnswerRepository->getTeacherPointsForInstitute($institute, $year);
        $pointsByTeacher = [];
        foreach ($teacherRows as $row) {
            $pointsByTeacher[$row['teacherId']] = $row;
        }

        $teachers = [];
        foreach ($institute->getTeacherOrganizations() as $teacherOrganization) {
            if (!$teacherOrganization->getRegular()) {
                continue;
            }
            $teacher = $teacherOrganization->getTeacher();
            $teacherId = $teacher->getId();
            $teachers[] = [
                'teacherId' => $teacherId,
                'fullName' => (string)$teacher,
                'points' => $pointsByTeacher[$teacherId]['points'] ?? 0.0,
                'hasPoints' => isset($pointsByTeacher[$teacherId]) && (float)$pointsByTeacher[$teacherId]['points'] > 0,
            ];
        }

        usort($teachers, static fn($a, $b) => $b['points'] <=> $a['points']);

        return $this->json([
            'institute' => [
                'id' => $institute->getId(),
                'name' => $institute->getName(),
                'reduction' => $institute->getReduction(),
            ],
            'teachers' => $teachers,
        ]);
    }

    #[Route('/organization/{orgId}/awards/{titleId}/{subId}/teachers', name: 'app_organization_awards_teachers_rating', methods: ['GET'])]
    public function organization_award_teachers(
        int $orgId,
        int $titleId,
        int $subId,
        Request $request,
        \App\Repository\TeacherAnswerRepository $teacherAnswerRepository
    ): JsonResponse
    {
        $year = $this->resolveYear($request);
        $subtitle = $this->questionSubtitleRepository->find($subId);
        if (!$subtitle || $subtitle->getTitle()->getId() !== $titleId) {
            throw new BadRequestHttpException('Invalid titleId/subId combination');
        }

        $rows = $teacherAnswerRepository->getOrganizationTeacherPointsBySubtitle($orgId, $subId, $year);
        $filtered = array_values(array_filter($rows, static fn(array $row) => (float)$row['point'] > 0));

        return $this->json([
            'titleId' => $titleId,
            'titleName' => $subtitle->getTitle()->getName(),
            'subId' => $subId,
            'subName' => $subtitle->getName(),
            'teachers' => $filtered,
        ]);
    }

}
