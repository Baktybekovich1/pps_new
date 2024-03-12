<?php

namespace App\Repository;

use App\Entity\PersonalAwardsSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonalAwardsSubtitle>
 *
 * @method PersonalAwardsSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalAwardsSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalAwardsSubtitle[]    findAll()
 * @method PersonalAwardsSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalAwardsSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalAwardsSubtitle::class);
    }

//    /**
//     * @return PersonalAwardsSubtitle[] Returns an array of PersonalAwardsSubtitle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PersonalAwardsSubtitle
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
