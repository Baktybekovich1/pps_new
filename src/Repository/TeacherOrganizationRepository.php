<?php

namespace App\Repository;

use App\Entity\TeacherOrganization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeacherOrganization>
 *
 * @method TeacherOrganization|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeacherOrganization|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeacherOrganization[]    findAll()
 * @method TeacherOrganization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherOrganizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeacherOrganization::class);
    }

//    /**
//     * @return TeacherOrganization[] Returns an array of TeacherOrganization objects
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

//    public function findOneBySomeField($value): ?TeacherOrganization
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
