<?php

namespace DWenzel\DataCollector\Service\Persistence;

use Doctrine\ORM\Mapping\Entity;
use DWenzel\DataCollector\Entity\Dto\DemandInterface;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Exception\InvalidUuidException;
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
 * Interface InstanceManagerInterface
 */
interface InstanceManagerInterface extends ManagerInterface
{
    /**
     * Get an instance
     *
     * @param DemandInterface $demand
     * @return Entity
     * @throws InvalidArgumentException
     * @throws InvalidUuidException
     */
    public function get(DemandInterface $demand): Instance;

    /**
     * Update an instance
     *
     * Saves any changes on instance.
     *
     * @param Instance $instance
     */
    public function update(Instance $instance): void;
}
