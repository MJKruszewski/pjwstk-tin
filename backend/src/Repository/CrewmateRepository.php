<?php

namespace App\Repository;

use App\Entity\Crewmate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Crewmate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Crewmate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Crewmate[]    findAll()
 * @method Crewmate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrewmateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crewmate::class);
    }

    // /**
    //  * @return Crewmate[] Returns an array of Crewmate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Crewmate
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
