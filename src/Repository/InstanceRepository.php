<?php

namespace DWenzel\DataCollector\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use DWenzel\DataCollector\Entity\Instance;

/**
 * @method Instance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instance[]    findAll()
 * @method Instance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instance::class);
    }

    /**
     * Add instance
     *
     * @param Instance $instance
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Instance $instance)
    {
        $this->getEntityManager()->persist($instance);
        $this->getEntityManager()->flush();
    }

    /**
     * Remove instance
     *
     * @param Instance $instance
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Instance $instance)
    {
        $this->getEntityManager()->remove($instance);
        $this->getEntityManager()->flush();
    }


}
