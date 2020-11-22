<?php

namespace App\Repository;

use App\Entity\StationTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StationTask|null find($id, $lockMode = null, $lockVersion = null)
 * @method StationTask|null findOneBy(array $criteria, array $orderBy = null)
 * @method StationTask[]    findAll()
 * @method StationTask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StationTask::class);
    }

    // /**
    //  * @return StationTask[] Returns an array of StationTask objects
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
    public function findOneBySomeField($value): ?StationTask
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
