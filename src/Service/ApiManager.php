<?php

namespace DWenzel\DataCollector\Service;

use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Dto\ApiDemand;
use DWenzel\DataCollector\Entity\Dto\DemandInterface;
use DWenzel\DataCollector\Entity\Dto\InstanceDemand;
use DWenzel\DataCollector\Entity\EntityInterface;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Repository\ApiRepository;
use InvalidArgumentException;

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
class ApiManager implements ApiManagerInterface
{

    /**
     * @var ApiRepository
     */
    protected $apiRepository;

    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    /**
     * @param DemandInterface $demand
     * @return EntityInterface
     * @throws InvalidUuidException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(DemandInterface $demand): EntityInterface
    {
        if (!$demand instanceof ApiDemand) {
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
                sprintf('An api with the identifier "%s" is already registered.', $identifier),
                1574174591
            );
        }

        $api = new Api();
        $api->setName($demand->getName())
            ->setVendor($demand->getVendor())
            ->setVersion($demand->getVersion())
            ->setIdentifier($demand->getIdentifier());

        $this->apiRepository->add($api);

        return $api;
    }

    /**
     * @inheritDoc
     */
    public function has(string $uuid): bool
    {
        return (bool)$this->apiRepository->count(
            ['identifier' => $uuid]
        );
    }

    /**
     * @inheritDoc
     * @param DemandInterface $demand
     * @throws InvalidUuidException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function forget(DemandInterface $demand): void
    {
        if (!$demand instanceof ApiDemand) {
            throw new InvalidArgumentException(
                sprintf('Can not process demand object of type %s .', get_class($demand)),
                1574174753
            );
        }

        $identifier = $demand->getIdentifier();
        if (!$this->has($identifier)) {
            throw new InvalidUuidException(
                sprintf(
                    'Can not forget API with identifier %s. There is no such API registered',
                    $identifier),
                1574175113
            );
        }
        $criteria = ['identifier' => $identifier];

        if ($instance = $this->apiRepository->findOneBy($criteria)) {
            $this->apiRepository->remove($instance);
        }
    }

}
