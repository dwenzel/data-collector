<?php

namespace DWenzel\DataCollector\Service\Queue;

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
 * Class CollectorQueue
 */
class CollectorQueue implements QueueInterface
{
    const DEFAULT_NAME = 'queue:default';

    /**
     * @var string
     */
    private $name;

    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage, string $name = self::DEFAULT_NAME)
    {
        $this->storage = $storage;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function pop()
    {
        return $this->storage->first($this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function push($value)
    {
        $this->storage->append($this->name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->storage->count($this->name);
    }
}