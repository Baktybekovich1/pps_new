<?php

namespace App\Repository;

use App\Entity\TeacherAnswer;
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

//    /**
//     * @return TeacherAnswer[] Returns an array of TeacherAnswer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TeacherAnswer
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
