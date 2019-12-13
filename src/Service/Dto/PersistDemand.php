<?php
declare(strict_types=1);

namespace DWenzel\DataCollector\Service\Dto;

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

/**
 * Demand for dumping data into a sink
 *
 * Class PersistDemand
 */
class PersistDemand implements ServiceDemandInterface
{
    /**
     * @var array
     */
    protected $parameter = [];

    /**
     * @var array
     */
    protected $payload = [];

    public function __construct(array $payload, array $parameter = [])
    {
        $this->parameter = $parameter;
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getParameter(): array
    {
        return $this->parameter;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
