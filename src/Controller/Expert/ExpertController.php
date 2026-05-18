<?php

namespace App\Controller\Expert;

use App\Entity\Expert;
use App\Entity\Teacher;
use App\Entity\Institute;
use App\Repository\ExpertRepository;
use App\Repository\TeacherRepository;
use App\Repository\InstituteRepository;
use App\Service\Expert\ExpertService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('')]
class ExpertController extends AbstractController
{
    public function __construct(
        private readonly ExpertRepository $expertRepository,
        private readonly TeacherRepository $teacherRepository,
        private readonly InstituteRepository $instituteRepository,
        private readonly ExpertService $expertService
    ) {
    }

    private function getExpert(UserInterface $user): ?Expert
    {
        if (!$user instanceof Teacher) {
            return null;
        }
        return $user->getExpert();
    }

    #[Route('/dashboard', name: 'api_expert_dashboard', methods: ['GET'])]
    public function dashboard(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $expert = $this->getExpert($user);
        if (!$expert) {
            return $this->json(['error' => 'You are not an expert'], 403);
        }

        $history = $this->expertService->getExpertHistory($expert);
        $historyData = [];
        foreach ($history as $a) {
            $historyData[] = [
                'id' => $a->getId(),
                'targetName' => $a->getTargetTeacher() ? (string)$a->getTargetTeacher() : ($a->getTargetInstitute() ? $a->getTargetInstitute()->getName() : 'N/A'),
                'points' => $a->getPoints(),
                'reason' => $a->getReason(),
                'isActive' => $a->isActive(),
                'createdAt' => $a->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json([
            'expertInfo' => [
                'id' => $expert->getId(),
                'jobTitle' => $expert->getJobTitle(),
                'fullName' => (string)$user,
            ],
            'history' => $historyData
        ]);
    }

    #[Route('/adjustment', name: 'api_expert_add_adjustment', methods: ['POST'])]
    public function addAdjustment(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $expert = $this->getExpert($user);
        if (!$expert) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $content = json_decode($request->getContent(), true);
        $targetType = $content['targetType'] ?? null; // 'teacher' or 'institute'
        $targetId = $content['targetId'] ?? null;
        $points = (int)($content['points'] ?? 0);
        $reason = $content['reason'] ?? '';

        if (!$targetType || !$targetId || !$points || !$reason) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        $targetTeacher = null;
        $targetInstitute = null;

        if ($targetType === 'teacher') {
            $targetTeacher = $this->teacherRepository->find($targetId);
            if (!$targetTeacher) return $this->json(['error' => 'Teacher not found'], 404);
        } elseif ($targetType === 'institute') {
            $targetInstitute = $this->instituteRepository->find($targetId);
            if (!$targetInstitute) return $this->json(['error' => 'Institute not found'], 404);
        } else {
            return $this->json(['error' => 'Invalid target type'], 400);
        }

        try {
            $this->expertService->addAdjustment($expert, $targetTeacher, $targetInstitute, $points, $reason);
            return $this->json(['message' => 'Adjustment added successfully']);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 409);
        }
    }

    #[Route('/targets', name: 'api_expert_targets', methods: ['GET'])]
    public function getTargets(): JsonResponse
    {
        if (!$this->isGranted('ROLE_EXPERT')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $teachers = $this->teacherRepository->findAll();
        $institutes = $this->instituteRepository->findAll();

        $teacherList = [];
        foreach ($teachers as $t) {
            $teacherList[] = [
                'id' => $t->getId(),
                'name' => (string)$t,
                'email' => $t->getEmail()
            ];
        }

        $instituteList = [];
        foreach ($institutes as $i) {
            $instituteList[] = [
                'id' => $i->getId(),
                'name' => $i->getName()
            ];
        }

        return $this->json([
            'teachers' => $teacherList,
            'institutes' => $instituteList
        ]);
    }
}
