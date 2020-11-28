<?php

namespace App\Repository;

use App\Entity\AuthorizationToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\SqlFormatter\Token;

/**
 * @method AuthorizationToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthorizationToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthorizationToken[]    findAll()
 * @method AuthorizationToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorizationTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthorizationToken::class);
    }

    /**
     * @param string $token
     * @return AuthorizationToken Returns an array of AuthorizationToken objects
     * @throws \Exception
     */
    public function findValidByToken(string $token): ?AuthorizationToken
    {
        return $this->createQueryBuilder('p')
            ->where('p.token = :token')
            ->andWhere('p.expireAt > :expireAt')
            ->setParameter('expireAt', new \DateTime())
            ->setParameter('token', $token)
            ->getQuery()
            ->getSingleResult();
    }

    /*
    public function findOneBySomeField($value): ?AuthorizationToken
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
