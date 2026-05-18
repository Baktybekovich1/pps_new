<?php

namespace App\Repository;

use App\Entity\InstituteAnswer;
use App\Entity\Years;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstituteAnswer>
 *
 * @method InstituteAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstituteAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstituteAnswer[]    findAll()
 * @method InstituteAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstituteAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstituteAnswer::class);
    }

    public function getInstitutePoints(\App\Entity\Institute $institute, ?\App\Entity\Years $currentYear = null): int
    {
        $qb = $this->createQueryBuilder('answer')
            ->select('COALESCE(SUM(subtitle.point), 0)')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->where('answer.institute = :institute')
            ->andWhere('answer.active = :active')
            ->setParameter('institute', $institute)
            ->setParameter('active', true);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return InstituteAnswer[] Returns an array of InstituteAnswer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InstituteAnswer
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function save(InstituteAnswer $answer): bool
    {
        try {
            $this->getEntityManager()->persist($answer);
            $this->getEntityManager()->flush();
            return true;
        } catch (\Throwable $exception) {
            return false;
        }
    }

    public function findByInstituteAndYear(\App\Entity\Institute $institute, ?Years $currentYear = null): array
    {
        $qb = $this->createQueryBuilder('answer')
            ->where('answer.institute = :institute')
            ->setParameter('institute', $institute);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneByIdAndInstituteAndYear(
        int $answerId,
        \App\Entity\Institute $institute,
        ?Years $currentYear = null
    ): ?InstituteAnswer {
        $qb = $this->createQueryBuilder('answer')
            ->where('answer.id = :answerId')
            ->andWhere('answer.institute = :institute')
            ->setParameter('answerId', $answerId)
            ->setParameter('institute', $institute)
            ->setMaxResults(1);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findOneByIdAndYear(int $answerId, ?Years $currentYear = null): ?InstituteAnswer
    {
        $qb = $this->createQueryBuilder('answer')
            ->where('answer.id = :answerId')
            ->setParameter('answerId', $answerId)
            ->setMaxResults(1);

        if ($currentYear) {
            $qb->andWhere('answer.academicYear = :currentYear')
                ->setParameter('currentYear', $currentYear);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
