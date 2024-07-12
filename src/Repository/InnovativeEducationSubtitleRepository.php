<?php

namespace App\Repository;

use App\Entity\InnovativeEducationSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InnovativeEducationSubtitle>
 *
 * @method InnovativeEducationSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method InnovativeEducationSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method InnovativeEducationSubtitle[]    findAll()
 * @method InnovativeEducationSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InnovativeEducationSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InnovativeEducationSubtitle::class);
    }

//    /**
//     * @return InnovativeEducationSubtitle[] Returns an array of InnovativeEducationSubtitle objects
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

//    public function findOneBySomeField($value): ?InnovativeEducationSubtitle
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function remove(InnovativeEducationSubtitle $obj): void
    {
        $this->getEntityManager()->remove($obj);
        $this->getEntityManager()->flush();
    }
}
