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

/**
 * Interface StorageInterface
 */
interface StorageInterface
{
    /**
     * Removes and returns the first element of the list.
     *
     * @param string $key The key name of list.
     *
     * @return mixed
     */
    public function first($key);

    /**
     * Append the value into the end of list.
     *
     * @param string $key   The key name of list.
     * @param mixed  $value Pushes value of the list.
     */
    public function append($key, $value);

    /**
     * Count all elements in a list.
     *
     * @param string $key The key name of list.
     *
     * @return int
     */
    public function count($key);
}