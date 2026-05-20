<?php

namespace App\Repository;

use App\Entity\Teacher;
use App\Entity\TeacherAnswer;
use App\Entity\Years;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeacherAnswer>
 *
 * @method TeacherAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeacherAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeacherAnswer[]    findAll()
 * @method TeacherAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeacherAnswer::class);
    }

    public function getTeacherPointsCount(Teacher $teacher, ?\App\Entity\Years $currentYear = null): int
    {
        $qb = $this->createQueryBuilder('answer')
            ->select('COALESCE(SUM(subtitle.point), 0)')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->where('teacher = :eteacher')
            ->setParameter('eteacher', $teacher);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function getTeacherPointsCountByTeacherId(int $teacherId, ?\App\Entity\Years $currentYear = null): int
    {
        $qb = $this->createQueryBuilder('answer')
            ->select('COALESCE(SUM(subtitle.point), 0)')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->where('teacher.id = :teacherId')
            ->setParameter('teacherId', $teacherId);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear OR stage.isPermanent = :isPermanent')
                ->setParameter('currentYear', $currentYear)
                ->setParameter('isPermanent', true);
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function getStaffTeacherPointsForInstitute(\App\Entity\Institute $institute, ?\App\Entity\Years $currentYear = null): int
    {
        $qb = $this->createQueryBuilder('answer')
            ->select('COALESCE(SUM(subtitle.point), 0)')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->innerJoin('teacher.teacherOrganizations', 'org')
            ->where('org.institute = :institute')
            ->andWhere('org.regular = :regular')
            ->andWhere('answer.active = :answerActive')
            ->setParameter('institute', $institute)
            ->setParameter('regular', true)
            ->setParameter('answerActive', true);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear OR stage.isPermanent = :isPermanent')
                ->setParameter('currentYear', $currentYear)
                ->setParameter('isPermanent', true);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function getTeacherPointsForInstitute(\App\Entity\Institute $institute, ?\App\Entity\Years $currentYear = null): array
    {
        $qb = $this->createQueryBuilder('answer')
            ->select('teacher.id AS teacherId')
            ->addSelect("CONCAT(COALESCE(teacher.lastName, ''), ' ', COALESCE(teacher.firstName, ''), ' ', COALESCE(teacher.middleName, '')) AS fullName")
            ->addSelect('COALESCE(SUM(subtitle.point), 0) AS points')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('teacher.teacherOrganizations', 'org')
            ->where('org.institute = :institute')
            ->andWhere('org.regular = :regular')
            ->andWhere('answer.active = :answerActive')
            ->setParameter('institute', $institute)
            ->setParameter('regular', true)
            ->setParameter('answerActive', true)
            ->groupBy('teacher.id')
            ->orderBy('points', 'DESC');

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        $rows = $qb->getQuery()->getArrayResult();

        return array_map(static fn(array $row) => [
            'teacherId' => (int)$row['teacherId'],
            'fullName' => trim((string)$row['fullName']),
            'points' => (float)$row['points'],
        ], $rows);
    }

    public function findTeacherAnswersByStage(int $teacherId, int $stageId): array
    {
        return $this->createQueryBuilder('answer')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->where('teacher.id = :teacherId')
            ->andWhere('stage.id = :stageId')
            ->setParameter('teacherId', $teacherId)
            ->setParameter('stageId', $stageId)
            ->getQuery()
            ->getResult();
    }

    public function findTeacherAnswersByStageAndYear(int $teacherId, int $stageId, ?Years $currentYear = null): array
    {
        $qb = $this->createQueryBuilder('answer')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->where('teacher.id = :teacherId')
            ->andWhere('stage.id = :stageId')
            ->setParameter('teacherId', $teacherId)
            ->setParameter('stageId', $stageId);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return $qb->getQuery()->getResult();
    }

    public function findActiveByTeacherAndYear(Teacher $teacher, ?Years $currentYear = null): array
    {
        $qb = $this->createQueryBuilder('answer')
            ->where('answer.teacher = :teacher')
            ->andWhere('answer.active = :active')
            ->setParameter('teacher', $teacher)
            ->setParameter('active', true);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByTeacherAndYear(Teacher $teacher, ?Years $currentYear = null): array
    {
        $qb = $this->createQueryBuilder('answer')
            ->where('answer.teacher = :teacher')
            ->setParameter('teacher', $teacher);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneByTeacherAndYear(int $answerId, Teacher $teacher, ?Years $currentYear = null): ?TeacherAnswer
    {
        $qb = $this->createQueryBuilder('answer')
            ->where('answer.id = :answerId')
            ->andWhere('answer.teacher = :teacher')
            ->setParameter('answerId', $answerId)
            ->setParameter('teacher', $teacher)
            ->setMaxResults(1);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getTeacherPointsCountByStage(int $teacherId, int $stageId, ?\App\Entity\Years $currentYear = null): int
    {
        $qb = $this->createQueryBuilder('answer')
            ->select('COALESCE(SUM(subtitle.point), 0)')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->where('teacher.id = :teacherId')
            ->andWhere('stage.id = :stageId')
            ->setParameter('teacherId', $teacherId)
            ->setParameter('stageId', $stageId);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear OR stage.isPermanent = :isPermanent')
                ->setParameter('currentYear', $currentYear)
                ->setParameter('isPermanent', true);
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function save(TeacherAnswer $answer): bool
    {
        try {
            $this->getEntityManager()->persist($answer);
            $this->getEntityManager()->flush();
            return true;
        } catch (\Throwable $exception) {
            return false;
        }
    }
    public function remove(TeacherAnswer $teacherAnswer): bool
    {
        try {
            $this->getEntityManager()->remove($teacherAnswer);
            $this->getEntityManager()->flush();
            return true;
        }catch (\Throwable $exception){
            return false;
        }
    }

}
