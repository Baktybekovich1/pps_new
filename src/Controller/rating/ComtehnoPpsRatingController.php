<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserOffenceRepository;
use App\Repository\TeacherRepository;
use App\Repository\TeacherAnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Rating')]
class ComtehnoPpsRatingController extends AbstractController
{


    public function __construct(
        private readonly UserInfoRepository                   $userInfoRepository,
        private readonly TeacherRepository                    $teacherRepository,
        private readonly TeacherAnswerRepository              $teacherAnswerRepository,
        private readonly UserOffenceRepository                $userOffenceRepository, 
        private readonly ExpertAdjustmentRepository           $expertAdjustmentRepository
    )
    {
    }

    #[Route('comtehno/pps', name: 'comtehno_pps_rating', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $pps = [];
        $teachers = $this->teacherRepository->findAll();

        foreach ($teachers as $teacher) {
            $firstOrg = $teacher->getTeacherOrganizations()->first();
            if (!$firstOrg) continue;

            $institute = $firstOrg->getInstitute();
            if ($institute->getUniversity() != 'Комтехно') {
                continue;
            }

            $fun = $this->getBigPoints($teacher);

            $pps[$teacher->getId()] = new PpsRatingDto(
                $teacher->getId(),
                (string)$teacher,
                $institute->getName(),
                $fun['research'],
                $fun['awards'],
                $fun['innovative'],
                $fun['social'],
                $fun['sum']
            );
        }

        return $this->json(['pps' => $pps]);
    }

    public function getBigPoints($teacher)
    {
        $answers = $this->teacherAnswerRepository->findBy(['teacher' => $teacher, 'active' => true]);
        
        $researchPoints = 0;
        $awardPoints = 0;
        $innovativePoints = 0;
        $socialPoints = 0;
        $sum = 0;

        foreach ($answers as $answer) {
            $points = $answer->getSubtitle()->getPoint();
            $sum += $points;
            
            $stageId = $answer->getSubtitle()->getTitle()->getStage()->getId();
            
            switch ($stageId) {
                case 1: $researchPoints += $points; break;
                case 2: $awardPoints += $points; break;
                case 3: $innovativePoints += $points; break;
                case 4: $socialPoints += $points; break;
            }
        }

        $offence = $this->userOffenceRepository->getUserPoints($teacher->getId());
        $sum -= $offence;

        $sum += $this->expertAdjustmentRepository->getTeacherAdjustedPoints($teacher->getId());
        
        return [
            'research' => $researchPoints, 
            'awards' => $awardPoints, 
            'innovative' => $innovativePoints, 
            'social' => $socialPoints, 
            'sum' => $sum
        ];
    }


}