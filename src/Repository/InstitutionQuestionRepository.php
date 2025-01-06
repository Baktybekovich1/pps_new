<?php

namespace App\Repository;

use App\Entity\InstitutionQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitutionQuestion>
 *
 * @method InstitutionQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitutionQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitutionQuestion[]    findAll()
 * @method InstitutionQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitutionQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitutionQuestion::class);
    }

//    /**
//     * @return InstitutionQuestion[] Returns an array of InstitutionQuestion objects
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

//    public function findOneBySomeField($value): ?InstitutionQuestion
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
