<?php

namespace App\Repository;

use App\Entity\QuestionTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionTitle>
 *
 * @method QuestionTitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionTitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionTitle[]    findAll()
 * @method QuestionTitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionTitle::class);
    }

//    /**
//     * @return QuestionTitle[] Returns an array of QuestionTitle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuestionTitle
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
