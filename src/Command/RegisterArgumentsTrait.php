<?php

namespace DWenzel\DataCollector\Command;

use DWenzel\DataCollector\Configuration\Argument\ArgumentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;

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
trait RegisterArgumentsTrait
{

    abstract protected function getArguments(): iterable;

    /**
     * Adds an argument.
     *
     * @param string $name The argument name
     * @param int|null $mode The argument mode: InputArgument::REQUIRED or InputArgument::OPTIONAL
     * @param string $description A description text
     * @param string|string[]|null $default The default value (for InputArgument::OPTIONAL mode only)
     *
     * @return $this
     * @throws InvalidArgumentException When argument mode is not valid
     */
    abstract public function addArgument($name, $mode = null, $description = '', $default = null);

    protected function registerArguments(): self
    {
        foreach ($this->getArguments() as $class) {
            /** @var ArgumentInterface $argument */
            $argument =  new $class();
            $this->addArgument(
                $argument->getName(),
                $argument->getMode(),
                $argument->getDescription(),
                $argument->getDefault()
            );
        }

        return $this;
    }

}
