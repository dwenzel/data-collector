<?php

namespace DWenzel\DataCollector\Service\Persistence;

use DWenzel\DataCollector\Service\Dto\DumpDemand;
use DWenzel\DataCollector\Service\Dto\ResultInterface;
use DWenzel\DataCollector\Service\Persistence\Backend\StorageBackendInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
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
class StorageService implements StorageServiceInterface
{
    /**
     * @var StorageBackendInterface
     */
    protected $backend;

    public function __construct(StorageBackendInterface $backend)
    {
        $this->backend = $backend;
    }

    public function dump(DumpDemand $dumpDemand): ResultInterface
    {
        // read parameter and payload from demand
        $parameter = [];
        $payload = [];
        return $this->backend->write($parameter, $payload);
    }
}
