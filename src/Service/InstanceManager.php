<?php

namespace DWenzel\DataCollector\Service;

use DWenzel\DataCollector\Entity\Dto\InstanceDemand;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Repository\InstanceRepository;
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
     * @param InstanceDemand $demand
     * @return Instance
     * @throws InvalidUuidException
     */
    public function register(InstanceDemand $demand): Instance
    {
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
    public
    function has(string $uuid): bool
    {
        return (bool)$this->instanceRepository->count(
            ['uuid' => $uuid]
        );
    }

    /**
     * @inheritDoc
     */
    public
    function deactivate(InstanceDemand $instanceDemand): void
    {
        // TODO: Implement deactivate() method.
    }

    /**
     * @inheritDoc
     */
    public
    function activate(InstanceDemand $instanceDemand): void
    {
        // TODO: Implement activate() method.
    }

    /**
     * @inheritDoc
     */
    public function forget(InstanceDemand $instanceDemand): void
    {
        $identifier = $instanceDemand->getIdentifier();

        $criteria = ['uuid' => $identifier];

        if ($instance = $this->instanceRepository->findOneBy($criteria))
        {
            $this->instanceRepository->remove($instance);
        }
    }

}