<?php

namespace DWenzel\DataCollector\Command;

use DWenzel\DataCollector\Configuration\Argument\ArgumentInterface;
use DWenzel\DataCollector\Configuration\Option\OptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

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
trait RegisterOptionsTrait
{

    abstract protected function getOptions(): iterable;

    /**
     * Adds an option.
     *
     * @param string                        $name        The option name
     * @param string|array|null             $shortcut    The shortcuts, can be null, a string of shortcuts delimited by | or an array of shortcuts
     * @param int|null                      $mode        The option mode: One of the InputOption::VALUE_* constants
     * @param string                        $description A description text
     * @param string|string[]|int|bool|null $default     The default value (must be null for InputOption::VALUE_NONE)
     *
     * @throws InvalidArgumentException If option mode is invalid or incompatible
     */
    public abstract function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null);

    protected function registerOptions(): void
    {
        foreach ($this->getOptions() as $class) {
            /** @var OptionInterface $option */
            $option =  new $class();
            $this->addOption(
                $option->getName(),
                $option->getShortCut(),
                $option->getMode(),
                $option->getDescription(),
                $option->getDefault()
            );
        }
    }

}
