<?php

namespace App\Repository;

use App\Entity\InstituteQuestionTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstituteQuestionTitle>
 *
 * @method InstituteQuestionTitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstituteQuestionTitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstituteQuestionTitle[]    findAll()
 * @method InstituteQuestionTitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstituteQuestionTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstituteQuestionTitle::class);
    }

//    /**
//     * @return InstituteQuestionTitle[] Returns an array of InstituteQuestionTitle objects
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

//    public function findOneBySomeField($value): ?InstituteQuestionTitle
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
