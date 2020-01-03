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
 * Interface QueueInterface
 */
interface QueueInterface
{
    /**
     * Gets the name of the queue list.
     *
     * @return string
     */
    public function getName();

    /**
     * Removes and returns the first element of the list.
     *
     * @return mixed
     */
    public function pop();

    /**
     * Append the value into the end of list.
     *
     * @param mixed $value Pushes value of the list.
     */
    public function push($value);

    /**
     * Count all elements in a list.
     *
     * @return int
     */
    public function count();
}