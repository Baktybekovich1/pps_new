<?php

namespace App\Repository;

use App\Entity\InstitutionAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitutionAnswer>
 *
 * @method InstitutionAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitutionAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitutionAnswer[]    findAll()
 * @method InstitutionAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitutionAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitutionAnswer::class);
    }

//    /**
//     * @return InstitutionAnswer[] Returns an array of InstitutionAnswer objects
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

//    public function findOneBySomeField($value): ?InstitutionAnswer
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
