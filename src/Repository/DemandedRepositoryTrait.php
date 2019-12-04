<?php

namespace DWenzel\DataCollector\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use DWenzel\DataCollector\Entity\EntityInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Trait DemandedRepositoryTrait
 */
trait DemandedRepositoryTrait
{
    /**
     * @return EntityManager
     */
    abstract protected function getEntityManager();

    /**
     * Remove instance
     *
     * @param EntityInterface $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(EntityInterface $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Add instance
     *
     * @param EntityInterface $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EntityInterface $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param EntityInterface $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(EntityInterface $entity)
    {
        $this->add($entity);
    }
}
