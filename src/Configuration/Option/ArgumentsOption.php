<?php

namespace DWenzel\DataCollector\Configuration\Option;

use DWenzel\DataCollector\Traits\Mode;
use Symfony\Component\Console\Input\InputOption;

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
 * Class DescriptionOption
 */
class ArgumentsOption extends InputOption implements OptionInterface
{
    use Mode;

    const NAME = 'arguments';
    const SHORTCUT = 'args';
    const DESCRIPTION = '(Optional) arguments.';
    const MODE = self::VALUE_OPTIONAL | self::VALUE_IS_ARRAY;

    public function __construct(
        string $name = self::NAME,
        string $shortcut = self::SHORTCUT,
        int $mode = self::MODE,
        string $description = self::DESCRIPTION,
        $default = null
    )
    {
        parent::__construct($name, $shortcut, $mode, $description, $default);
    }
}
