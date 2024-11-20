<?php

namespace App\Repository;

use App\Entity\ResultsOfYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResultsOfYear>
 *
 * @method ResultsOfYear|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultsOfYear|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultsOfYear[]    findAll()
 * @method ResultsOfYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultsOfYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultsOfYear::class);
    }

//    /**
//     * @return ResultsOfYear[] Returns an array of ResultsOfYear objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ResultsOfYear
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(ResultsOfYear $resultsOfYear): void
    {
        $this->getEntityManager()->persist($resultsOfYear);
        $this->getEntityManager()->flush();

    }
}
