<?php

namespace DWenzel\DataCollector\Service;

use Doctrine\DBAL\Types\GuidType;
use DWenzel\DataCollector\Entity\Dto\InstanceDemand;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Exception\InvalidUuidException;

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
interface InstanceManagerInterface
{
    /**
     * Register an instance
     *
     * @param InstanceDemand $demand
     * @return Instance
     * @throws InvalidUuidException
     */
    public function register(InstanceDemand $demand): Instance;

    /**
     * @param string $uuid Tells whether an instance with the guid exists
     * @return bool
     */
    public function has(string $uuid): bool;

    /**
     * Deactivate an instance.
     *
     * I.e. stop collecting data from instance.
     *
     * @param InstanceDemand $instanceDemand
     */
    public function deactivate(InstanceDemand $instanceDemand): void;

    /**
     * Activate instance.
     *
     * I.e. start collecting data from instance.
     *
     * @param InstanceDemand $instanceDemand
     */
    public function activate(InstanceDemand $instanceDemand): void;

    /**
     * Forget instance
     *
     * Remove instance from data collector. All registration data will be deleted!
     *
     * @param InstanceDemand $instanceDemand
     */
    public function forget(InstanceDemand $instanceDemand): void;

}