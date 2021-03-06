<?php

namespace App\Repository;

use App\Entity\Attraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Attraction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attraction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attraction[]    findAll()
 * @method Attraction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attraction::class);
    }


   /**
     * @return Query
     */

    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('a')
        ->getQuery();
    }

    /**
     * @return Attraction[]
     */
    public function findLatest(): array
    {
        return $this->createQueryBuilder('a')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Attraction[] Returns an array of Attraction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Attraction
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
