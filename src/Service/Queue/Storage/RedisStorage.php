<?php

namespace DWenzel\DataCollector\Service\Queue\Storage;

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

use SymfonyBundles\RedisBundle\Redis\ClientInterface;
use function serialize;
use function unserialize;

class RedisStorage implements StorageInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function first($key)
    {
        return unserialize($this->client->pop($key));
    }

    /**
     * {@inheritdoc}
     */
    public function append($key, $value)
    {
        $this->client->push($key, serialize($value));
    }

    /**
     * {@inheritdoc}
     */
    public function count($key)
    {
        return $this->client->count($key);
    }
}
