<?php

namespace DWenzel\DataCollector\Service\Persistence\Backend;

use DWenzel\DataCollector\Message\Error;
use DWenzel\DataCollector\Service\Dto\DumpResult;
use DWenzel\DataCollector\Service\Dto\ResultInterface;
use InfluxDB\Client;
use InfluxDB\Database;
use InfluxDB\Exception;

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
class InfluxDBBackend implements StorageBackendInterface
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client = null)
    {
        if (null !== $client) {
            $this->client = $client;
        }
    }

    /**
     * @param array $parameters
     * @param string|array $payload
     * @return ResultInterface
     */
    public function write(array $parameters, $payload): ResultInterface
    {
        $result = new DumpResult();

        $success = $this->client->write($parameters, $payload);
        if (!$success) {
            $message = new Error();
        }
    }

    /**
     * @return iterable
     * @throws Exception
     */
    public function listDatabases(): iterable
    {
        return $this->client->listDatabases();
    }

    /**
     * @param string $name
     * @return object
     */
    public function selectDatabase(string $name): object
    {
        return $this->client->selectDB($name);
    }
}
