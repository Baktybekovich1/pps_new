<?php

namespace App\Repository;

use App\Entity\Teacher;
use App\Entity\TeacherAnswer;
use App\Entity\Years;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    /**
     * The one rule for which answers score: active ones, given in the year
     * asked about or for a stage that does not expire.
     */
    private function scoring(QueryBuilder $qb, ?Years $year): QueryBuilder
    {
        $qb->andWhere('answer.active = :scoringActive')->setParameter('scoringActive', true);

        if ($year) {
            $qb->andWhere('answer.academicYear = :scoringYear OR stage.isPermanent = :scoringPermanent')
                ->setParameter('scoringYear', $year)
                ->setParameter('scoringPermanent', true);
        }

        return $qb;
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

        $this->scoring($qb, $currentYear);

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

        $this->scoring($qb, $currentYear);

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
            ->setParameter('institute', $institute)
            ->setParameter('regular', true);

        $this->scoring($qb, $currentYear);

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
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->innerJoin('teacher.teacherOrganizations', 'org')
            ->where('org.institute = :institute')
            ->andWhere('org.regular = :regular')
            ->setParameter('institute', $institute)
            ->setParameter('regular', true)
            ->groupBy('teacher.id')
            ->orderBy('points', 'DESC');

        $this->scoring($qb, $currentYear);

        $rows = $qb->getQuery()->getArrayResult();

        return array_map(static fn(array $row) => [
            'teacherId' => (int)$row['teacherId'],
            'fullName' => trim((string)$row['fullName']),
            'points' => (float)$row['points'],
        ], $rows);
    }

    public function getOrganizationTeacherPointsBySubtitle(
        int $organizationId,
        int $subtitleId,
        ?\App\Entity\Years $currentYear = null
    ): array {
        $qb = $this->createQueryBuilder('answer')
            ->select('teacher.id AS teacherId')
            ->addSelect("CONCAT(COALESCE(teacher.lastName, ''), ' ', COALESCE(teacher.firstName, ''), ' ', COALESCE(teacher.middleName, '')) AS fullName")
            ->addSelect('institute.name AS instituteName')
            ->addSelect('COALESCE(SUM(subtitle.point), 0) AS points')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->innerJoin('teacher.teacherOrganizations', 'org')
            ->innerJoin('org.institute', 'institute')
            ->where('org.organization = :organizationId')
            ->andWhere('org.regular = :regular')
            ->andWhere('subtitle.id = :subtitleId')
            ->setParameter('organizationId', $organizationId)
            ->setParameter('regular', true)
            ->setParameter('subtitleId', $subtitleId)
            ->groupBy('teacher.id, institute.name')
            ->orderBy('points', 'DESC');

        $this->scoring($qb, $currentYear);

        $rows = $qb->getQuery()->getArrayResult();

        return array_map(static fn(array $row) => [
            'teacherId' => (int)$row['teacherId'],
            'name' => trim((string)$row['fullName']),
            'institute' => (string)$row['instituteName'],
            'point' => (float)$row['points'],
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

        $this->scoring($qb, $currentYear);

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
