<?php

namespace DWenzel\DataCollector\Factory;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Repository\InstanceRepository;
use DWenzel\DataCollector\Service\Dto\ApiCallDemand;
use DWenzel\DataCollector\Service\Queue\CollectorQueue;
use DWenzel\DataCollector\Service\Queue\QueueInterface;
use DWenzel\DataCollector\Service\Queue\Storage\StorageInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 Dirk Wenzel
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
 * Class CollectorQueueFactory
 */
class CollectorQueueFactory
{
    const CRITERIA = [];

    public static function create(InstanceRepository $instanceRepository, StorageInterface $storage): QueueInterface
    {
        /** @var InstanceRepository $instanceRepository */
        $instances = $instanceRepository->findBy(static::CRITERIA);
        $queue = new CollectorQueue($storage);
        /** @var Instance $instance */
        foreach ($instances as $instance) {
            $urls = $instance->getUrls();
            foreach ($urls as $url) {
                $demand = new ApiCallDemand($url);
                $queue->push($demand);
            }
        }

        return $queue;
    }

}
