<?php

namespace App\Repository;

use App\Entity\Director;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Director>
 *
 * @method Director|null find($id, $lockMode = null, $lockVersion = null)
 * @method Director|null findOneBy(array $criteria, array $orderBy = null)
 * @method Director[]    findAll()
 * @method Director[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Director::class);
    }

//    /**
//     * @return Director[] Returns an array of Director objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Director
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function save(Director $director): bool
    {
        try {
            $this->getEntityManager()->persist($director);
            $this->getEntityManager()->flush();
            return true;
        } catch (\Throwable $exception) {
            return false;
        }
    }
    public function remove(Director $director): bool
    {
        try {
            $this->getEntityManager()->remove($director);
            $this->getEntityManager()->flush();
            return true;
        }catch (\Throwable $exception){
            return false;
        }
    }

    public function directorCount(int $teacherId):int
    {
        $qb = $this->createQueryBuilder('director');
        $qb->select('count(director.id)');
        $qb->where('director.teacherId = :teacherId');
        $qb->setParameter('teacherId', $teacherId);
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
