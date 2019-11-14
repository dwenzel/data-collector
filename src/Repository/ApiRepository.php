<?php

namespace DWenzel\DataCollector\Repository;

use DWenzel\DataCollector\Entity\Api;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Api|null find($id, $lockMode = null, $lockVersion = null)
 * @method Api|null findOneBy(array $criteria, array $orderBy = null)
 * @method Api[]    findAll()
 * @method Api[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Api::class);
    }

    // /**
    //  * @return Api[] Returns an array of Api objects
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
    public function findOneBySomeField($value): ?Api
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
