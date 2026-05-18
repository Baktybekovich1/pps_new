<?php

namespace App\Service\Expert;

use App\Entity\Expert;
use App\Entity\ExpertAdjustment;
use App\Entity\Teacher;
use App\Entity\Institute;
use App\Repository\ExpertAdjustmentRepository;
use App\Repository\YearsRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExpertService
{
    public function __construct(
        private readonly ExpertAdjustmentRepository $expertAdjustmentRepository,
        private readonly YearsRepository $yearsRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function addAdjustment(
        Expert $expert, 
        ?Teacher $targetTeacher, 
        ?Institute $targetInstitute, 
        int $points, 
        string $reason
    ): ExpertAdjustment {
        $currentYear = $this->yearsRepository->findCurrentYear();
        if (!$currentYear) {
            throw new \RuntimeException('Текущий год не установлен. Обратитесь к администратору.', 409);
        }

        $adjustment = new ExpertAdjustment();
        $adjustment->setExpert($expert);
        $adjustment->setTargetTeacher($targetTeacher);
        $adjustment->setTargetInstitute($targetInstitute);
        $adjustment->setPoints($points);
        $adjustment->setReason($reason);
        $adjustment->setIsActive(true);
        $adjustment->setAcademicYear($currentYear);

        $this->entityManager->persist($adjustment);
        $this->entityManager->flush();

        return $adjustment;
    }

    /**
     * @return ExpertAdjustment[]
     */
    public function getExpertHistory(Expert $expert): array
    {
        return $this->expertAdjustmentRepository->findBy(['expert' => $expert], ['createdAt' => 'DESC']);
    }

    /**
     * @return ExpertAdjustment[]
     */
    public function getTeacherAdjustments(Teacher $teacher): array
    {
        return $this->expertAdjustmentRepository->findBy(['targetTeacher' => $teacher], ['createdAt' => 'DESC']);
    }
}
