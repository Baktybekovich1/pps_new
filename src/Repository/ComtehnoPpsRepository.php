<?php

namespace App\Repository;

use App\Entity\ComtehnoPps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComtehnoPps>
 *
 * @method ComtehnoPps|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComtehnoPps|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComtehnoPps[]    findAll()
 * @method ComtehnoPps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComtehnoPpsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComtehnoPps::class);
    }

//    /**
//     * @return ComtehnoPps[] Returns an array of ComtehnoPps objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ComtehnoPps
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
