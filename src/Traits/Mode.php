<?php

namespace DWenzel\DataCollector\Traits;

use DWenzel\DataCollector\Configuration\Argument\EndPointArgument;
use Symfony\Component\Console\Input\InputArgument;

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
trait Mode
{
    protected $mode = InputArgument::REQUIRED;

    /**
     * @return int
     */
    public function getMode()
    {
        if (defined(get_class($this). '::MODE')) {
            return self::MODE;
        }
        return $this->mode;
    }
}
