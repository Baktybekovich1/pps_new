<?php

namespace App\Repository;

use App\Entity\Years;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Years>
 *
 * @method Years|null find($id, $lockMode = null, $lockVersion = null)
 * @method Years|null findOneBy(array $criteria, array $orderBy = null)
 * @method Years[]    findAll()
 * @method Years[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YearsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Years::class);
    }


    public function save(Years $years)
    {
        $this->getEntityManager()->persist($years);
        $this->getEntityManager()->flush();
    }

    public function remove(Years $years)
    {
        $this->getEntityManager()->remove($years);
        $this->getEntityManager()->flush();
    }

}
