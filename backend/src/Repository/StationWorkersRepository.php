<?php

namespace App\Repository;

use App\Entity\StationWorkers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StationWorkers|null find($id, $lockMode = null, $lockVersion = null)
 * @method StationWorkers|null findOneBy(array $criteria, array $orderBy = null)
 * @method StationWorkers[]    findAll()
 * @method StationWorkers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationWorkersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StationWorkers::class);
    }

    // /**
    //  * @return StationWorkers[] Returns an array of StationWorkers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StationWorkers
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
