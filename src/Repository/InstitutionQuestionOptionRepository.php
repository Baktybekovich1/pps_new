<?php

namespace App\Repository;

use App\Entity\InstitutionQuestionOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstitutionQuestionOption>
 *
 * @method InstitutionQuestionOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitutionQuestionOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitutionQuestionOption[]    findAll()
 * @method InstitutionQuestionOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitutionQuestionOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstitutionQuestionOption::class);
    }

//    /**
//     * @return InstitutionQuestionOption[] Returns an array of InstitutionQuestionOption objects
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

//    public function findOneBySomeField($value): ?InstitutionQuestionOption
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
