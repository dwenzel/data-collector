<?php

namespace DWenzel\DataCollector\Service\Persistence;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use DWenzel\DataCollector\Entity\Dto\DemandInterface;
use DWenzel\DataCollector\Entity\Dto\InstanceDemand;
use DWenzel\DataCollector\Entity\EntityInterface;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Repository\InstanceRepository;
use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

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
 * Class InstanceManager
 */
class InstanceManager implements InstanceManagerInterface
{

    /**
     * @var InstanceRepository
     */
    protected $instanceRepository;

    public function __construct(InstanceRepository $instanceRepository)
    {
        $this->instanceRepository = $instanceRepository;
    }

    /**
     * @param DemandInterface $demand
     * @return EntityInterface
     * @throws InvalidUuidException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function register(DemandInterface $demand): EntityInterface
    {
        if (!$demand instanceof InstanceDemand) {
            throw new InvalidArgumentException(
                sprintf(
                    'Can not register instance from demand object of type %s.',
                    get_class($demand)
                ),
                1574174581
            );
        }
        $identifier = $demand->getIdentifier();
        if (!empty($identifier)
            && $this->has($demand->getIdentifier())) {
            throw new InvalidUuidException(
                sprintf('An instance with the identifier "%s" is already registered.', $identifier),
                1573677985
            );
        }

        if (empty($identifier)) {
            $identifier = Uuid::uuid4()->toString();
            $demand->setIdentifier($identifier);
        }

        if (!Uuid::isValid($identifier)) {
            throw  new InvalidUuidException(
                sprintf('The identifier "%s" is is not a valid Universal Unique ID', $identifier),
                1573690590
            );
        }

        $instance = new Instance();
        $instance->setName($demand->getName())
            ->setRole($demand->getRole())
            ->setUuid($demand->getIdentifier());

        $this->instanceRepository->add($instance);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function has(string $identifier): bool
    {
        return (bool)$this->instanceRepository->count(
            ['uuid' => $identifier]
        );
    }

    /**
     * @inheritDoc
     * @param DemandInterface $demand
     * @throws InvalidUuidException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function forget(DemandInterface $demand): void
    {
        $this->validateDemand($demand);
        /** @var InstanceDemand $demand */
        $identifier = $demand->getIdentifier();

        if (!$this->has($identifier)) {
            throw new InvalidUuidException(
                sprintf(
                    'Can not forget instance with UUID %s. There is no such instance registered',
                    $identifier),
                1573766261
            );
        }
        $criteria = ['uuid' => $identifier];

        if ($instance = $this->instanceRepository->findOneBy($criteria)) {
            $this->instanceRepository->remove($instance);
        }
    }

    /**
     * @param DemandInterface $demand
     * @return Instance
     * @throws InvalidUuidException
     */
    public function get(DemandInterface $demand): Instance
    {
        $this->validateDemand($demand);

        /** @var InstanceDemand $demand */
        $identifier = $demand->getIdentifier();

        $instance = $this->instanceRepository->findOneBy(
            ['uuid' => $identifier]
        );

        if (null === $instance) {
            throw new InvalidUuidException(
                sprintf(
                    'Cannot get instance with UUID %s. There is no such instance registered',
                    $identifier),
                1575289588
            );
        }

        return $instance;
    }

    /**
     * @param Instance $instance
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Instance $instance): void
    {
        $this->instanceRepository->update($instance);
    }

    /**
     * @param DemandInterface $demand
     */
    protected function validateDemand(DemandInterface $demand): void
    {
        if (!$demand instanceof InstanceDemand) {
            throw new InvalidArgumentException(
                sprintf('Can not process demand object of type %s .', get_class($demand)),
                1574174753
            );
        }
    }

}
