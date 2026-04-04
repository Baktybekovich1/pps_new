<?php

namespace App\Controller\rating;

use App\Dto\RatingDto\PpsRatingDto;
use App\Dto\RatingDto\QuestionPPSRatingDto;
use App\Repository\QuestionSubtitleRepository;
use App\Repository\QuestionTitleRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
#[OA\Tag(name: 'Rating')]
class QuestionItecRatingController extends AbstractController
{


    public function __construct(
        private readonly UserInfoRepository           $userInfoRepository,
        private readonly TeacherRepository            $teacherRepository,
        private readonly TeacherAnswerRepository      $teacherAnswerRepository,
        private readonly QuestionTitleRepository      $questionTitleRepository,
        private readonly QuestionSubtitleRepository   $questionSubtitleRepository
    )
    {
    }

    private function getCommonQuestionRating(Request $request, string $university): array
    {
        $titleId = $request->get('titleId');
        $subId = $request->get('subId');
        
        if (!$titleId) return [];
        
        $pps = [];
        $teachers = $this->teacherRepository->findAll();
        
        foreach ($teachers as $teacher) {
            $firstOrg = $teacher->getTeacherOrganizations()->first();
            if (!$firstOrg || $firstOrg->getInstitute()->getUniversity() !== $university) {
                continue;
            }

            $criteria = ['teacher' => $teacher, 'active' => true];
            $answers = $this->teacherAnswerRepository->findBy($criteria);
            
            $points = 0;
            foreach ($answers as $answer) {
                $subtitle = $answer->getSubtitle();
                if ($subtitle->getTitle()->getId() != $titleId) {
                    continue;
                }
                
                if ($subId && $subtitle->getId() != $subId) {
                    continue;
                }
                
                $points += $subtitle->getPoint();
            }
            
            if ($points > 0) {
                $pps[] = new QuestionPPSRatingDto(
                    $teacher->getId(),
                    (string)$teacher,
                    $firstOrg->getInstitute()->getName(),
                    $points
                );
            }
        }
        
        return $pps;
    }

    #[Route('/question/itec/awards/{titleId}/{subId}', name: 'app_question_itec_awards',methods: ['GET'])]
    public function getPps(Request $request): JsonResponse
    {
        return $this->json($this->getCommonQuestionRating($request, 'КИТЭ'));
    }

    #[Route('/question/itec/research/{titleId}/{subId}', name: 'app_question_itec_research',methods: ['GET'])]
    public function getResearchPps(Request $request): JsonResponse
    {
        return $this->json($this->getCommonQuestionRating($request, 'КИТЭ'));
    }

    #[Route('/question/itec/innovative/{titleId}/{subId}', name: 'app_question_itec_innovative',methods: ['GET'])]
    public function getInnovativePps(Request $request): JsonResponse
    {
        return $this->json($this->getCommonQuestionRating($request, 'КИТЭ'));
    }

    #[Route('/question/itec/social/{titleId}/{subId}', name: 'app_question_itec_social',methods: ['GET'])]
    public function getSocialPps(Request $request): JsonResponse
    {
        return $this->json($this->getCommonQuestionRating($request, 'КИТЭ'));
    }

}