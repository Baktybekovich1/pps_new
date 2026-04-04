<?php

namespace App\Controller\Admin;

use App\Entity\Expert;
use App\Entity\Teacher;
use App\Repository\ExpertRepository;
use App\Repository\TeacherRepository;
use App\Repository\ExpertAdjustmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/experts')]
class AdminExpertController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExpertRepository $expertRepository,
        private readonly TeacherRepository $teacherRepository,
        private readonly ExpertAdjustmentRepository $adjustmentRepository
    ) {
    }

    #[Route('', name: 'api_admin_experts_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $experts = $this->expertRepository->findAll();
        $data = [];
        foreach ($experts as $expert) {
            $teacher = $expert->getTeacher();
            $data[] = [
                'id' => $expert->getId(),
                'teacherId' => $teacher ? $teacher->getId() : null,
                'fullName' => $teacher ? (string)$teacher : 'N/A',
                'jobTitle' => $expert->getJobTitle(),
            ];
        }

        return $this->json($data);
    }

    #[Route('', name: 'api_admin_experts_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $content = json_decode($request->getContent(), true);
        $teacherId = $content['teacherId'] ?? null;
        $jobTitle = $content['jobTitle'] ?? null;

        if (!$teacherId || !$jobTitle) {
            return $this->json(['error' => 'Teacher ID and Job Title are required'], 400);
        }

        $teacher = $this->teacherRepository->find($teacherId);
        if (!$teacher) {
            return $this->json(['error' => 'Teacher not found'], 404);
        }

        // Check if already an expert
        if ($teacher->getExpert()) {
            return $this->json(['error' => 'This teacher is already an expert'], 400);
        }

        $expert = new Expert();
        $expert->setTeacher($teacher);
        $expert->setJobTitle($jobTitle);

        // Add ROLE_EXPERT to teacher
        $roles = $teacher->getRoles();
        if (!in_array('ROLE_EXPERT', $roles)) {
            $roles[] = 'ROLE_EXPERT';
            $teacher->setRoles($roles);
        }

        $this->entityManager->persist($expert);
        $this->entityManager->flush();

        return $this->json(['message' => 'Expert created', 'id' => $expert->getId()]);
    }

    #[Route('/{id}', name: 'api_admin_experts_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $expert = $this->expertRepository->find($id);
        if (!$expert) {
            return $this->json(['error' => 'Expert not found'], 404);
        }

        $content = json_decode($request->getContent(), true);
        $jobTitle = $content['jobTitle'] ?? null;

        if ($jobTitle) {
            $expert->setJobTitle($jobTitle);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Expert updated']);
    }

    #[Route('/{id}', name: 'api_admin_experts_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $expert = $this->expertRepository->find($id);
        if (!$expert) {
            return $this->json(['error' => 'Expert not found'], 404);
        }

        $teacher = $expert->getTeacher();
        if ($teacher) {
            $roles = $teacher->getRoles();
            $roles = array_diff($roles, ['ROLE_EXPERT']);
            $teacher->setRoles(array_values($roles));
        }

        $this->entityManager->remove($expert);
        $this->entityManager->flush();

        return $this->json(['message' => 'Expert deleted']);
    }

    #[Route('/adjustments/all', name: 'api_admin_adjustments_list', methods: ['GET'])]
    public function allAdjustments(): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $adjustments = $this->adjustmentRepository->findBy([], ['createdAt' => 'DESC']);
        $data = [];
        foreach ($adjustments as $a) {
            $data[] = [
                'id' => $a->getId(),
                'expertName' => $a->getExpert()->getJobTitle() . ' (' . (string)$a->getExpert()->getTeacher() . ')',
                'targetName' => $a->getTargetTeacher() ? (string)$a->getTargetTeacher() : ($a->getTargetInstitute() ? $a->getTargetInstitute()->getName() : 'N/A'),
                'points' => $a->getPoints(),
                'reason' => $a->getReason(),
                'isActive' => $a->isActive(),
                'createdAt' => $a->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/adjustments/{id}/toggle', name: 'api_admin_adjustments_toggle', methods: ['POST'])]
    public function toggleAdjustment(int $id): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $adjustment = $this->adjustmentRepository->find($id);
        if (!$adjustment) {
            return $this->json(['error' => 'Adjustment not found'], 404);
        }

        $adjustment->setIsActive(!$adjustment->isActive());
        $this->entityManager->flush();

        return $this->json(['message' => 'Adjustment status toggled', 'isActive' => $adjustment->isActive()]);
    }
}
