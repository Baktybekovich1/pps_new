<?php

namespace App\Repository;

use App\Entity\Expert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expert>
 *
 * @method Expert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expert[]    findAll()
 * @method Expert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expert::class);
    }

    public function save($expert): void
    {
        $this->getEntityManager()->persist($expert);
        $this->getEntityManager()->flush();
    }

    public function remove($expert): void
    {
        $this->getEntityManager()->remove($expert);
        $this->getEntityManager()->flush();
    }
}
