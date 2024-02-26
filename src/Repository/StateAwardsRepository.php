<?php

namespace App\Repository;

use App\Entity\StateAwards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StateAwards>
 *
 * @method StateAwards|null find($id, $lockMode = null, $lockVersion = null)
 * @method StateAwards|null findOneBy(array $criteria, array $orderBy = null)
 * @method StateAwards[]    findAll()
 * @method StateAwards[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateAwardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StateAwards::class);
    }

//    /**
//     * @return StateAwards[] Returns an array of StateAwards objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StateAwards
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
