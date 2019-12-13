<?php
declare(strict_types=1);

namespace DWenzel\DataCollector\Service\Persistence;

use DWenzel\DataCollector\Service\Dto\PersistDemand;
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

    public function persist(PersistDemand $demand): ResultInterface
    {
        return $this->backend->write(
            $demand->getParameter(),
            $demand->getPayload()
        );
    }
}
