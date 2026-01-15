<?php

namespace App\Repository;

use App\Entity\QuestionSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionSubtitle>
 *
 * @method QuestionSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionSubtitle[]    findAll()
 * @method QuestionSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionSubtitle::class);
    }

//    /**
//     * @return QuestionSubtitle[] Returns an array of QuestionSubtitle objects
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

//    public function findOneBySomeField($value): ?QuestionSubtitle
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
