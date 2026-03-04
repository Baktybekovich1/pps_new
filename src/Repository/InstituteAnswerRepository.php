<?php

namespace App\Repository;

use App\Entity\InstituteAnswer;
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
}
