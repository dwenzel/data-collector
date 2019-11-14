<?php

namespace DWenzel\DataCollector\Configuration\Argument;

use DWenzel\DataCollector\Traits\Mode;
use Symfony\Component\Console\Input\InputArgument;

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
 * Class Role
 */
class Role extends InputArgument implements ArgumentInterface
{
    use Mode;

    const NAME = 'role';
    const DESCRIPTION = 'Role of instance e.g. `production`, `staging`, `testing` ';
    const MODE = InputArgument::OPTIONAL;

    public function __construct(
        string $name = self::NAME,
        int $mode = self::MODE,
        string $description = self::DESCRIPTION,
        $default = null
    )
    {
        parent::__construct($name, $mode, $description, $default);
    }
}