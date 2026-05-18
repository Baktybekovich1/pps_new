<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Entity\Years;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\YearsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Rating')]
class ItecPpsRatingController extends AbstractController
{
    public function __construct(
        private readonly TeacherRepository                    $teacherRepository,
        private readonly TeacherAnswerRepository              $teacherAnswerRepository,
        private readonly UserOffenceRepository                $userOffenceRepository,
        private readonly ExpertAdjustmentRepository           $expertAdjustmentRepository,
        private readonly YearsRepository                     $yearsRepository
    )
    {
    }

    private function resolveYear(Request $request): ?Years
    {
        $yearId = $request->query->get('yearId');
        if ($yearId) {
            return $this->yearsRepository->find((int)$yearId);
        }
        return $this->yearsRepository->findCurrentYear();
    }

    #[Route('itec/pps', name: 'itec_pps_rating', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $year = $this->resolveYear($request);
        $pps = [];
        $teachers = $this->teacherRepository->findAll();

        foreach ($teachers as $teacher) {
            $firstOrg = $teacher->getTeacherOrganizations()->first();
            if (!$firstOrg) continue;

            $institute = $firstOrg->getInstitute();
            if ($institute->getUniversity() != 'КИТЭ') {
                continue;
            }

            $fun = $this->getBigPoints($teacher, $year);

            $pps[$teacher->getId()] = new PpsRatingDto(
                $teacher->getId(),
                (string)$teacher,
                $institute->getName(),
                $fun['research'],
                $fun['awards'],
                $fun['innovative'],
                $fun['social'],
                $fun['sum'],
                $fun['expert']
            );
        }

        return $this->json(['pps' => $pps]);
    }

    public function getBigPoints($teacher, ?Years $year = null): array
    {
        $researchPoints   = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 1, $year);
        $awardPoints      = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 2, $year);
        $innovativePoints = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 3, $year);
        $socialPoints     = $this->teacherAnswerRepository->getTeacherPointsCountByStage($teacher->getId(), 4, $year);

        $sum = $researchPoints + $awardPoints + $innovativePoints + $socialPoints;

        $offence = $this->userOffenceRepository->getUserPoints($teacher->getId());
        $sum -= $offence;

        $expertPoints = $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacher->getId(), $year);
        $sum += $expertPoints;

        return [
            'research'   => $researchPoints,
            'awards'     => $awardPoints,
            'innovative' => $innovativePoints,
            'social'     => $socialPoints,
            'sum'        => $sum,
            'expert'     => $expertPoints
        ];
    }
}
