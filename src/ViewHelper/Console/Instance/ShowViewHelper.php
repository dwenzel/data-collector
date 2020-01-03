<?php

namespace DWenzel\DataCollector\ViewHelper\Console\Instance;

use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\ViewHelper\ViewHelperInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
 * Class ShowViewHelper
 */
class ShowViewHelper implements ViewHelperInterface
{
    const INSTANCE_SHOW_MESSAGE = <<<ISM

<info>identifier (uuid)</info>:  %s
<info>id (local)</info>:         %s
<info>name</info>:               %s
<info>role</info>:               %s    
ISM;

    const API_HEADER = <<<AHT

Registered APIs:
AHT;

    const API_SINGLE_FORMAT = <<<ASF
    
<info>vendor</info>     : %s
<info>name</info>       : %s
<info>version</info>    : %s
<info>identifier</info> : %s
ASF;


    /**
     * @var Instance
     */
    protected $instance;

    /**
     * @var
     */
    protected $output;

    public function __construct(OutputInterface $output, Instance $instance = null)
    {
        $this->output = $output;
        $this->instance = $instance ?? $instance;

    }

    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;
    }

    public function render()
    {
        if (!$this->instance instanceof Instance) {
            return;
        }
        $lines = [];
        $lines[] = sprintf(
            static::INSTANCE_SHOW_MESSAGE,
            $this->instance->getUuid(),
            $this->instance->getId(),
            $this->instance->getName(),
            $this->instance->getRole()
        );
        $apis = $this->instance->getApis();
        if (!$apis->isEmpty()) {
            $lines[] = static::API_HEADER;
            foreach ($apis as $api) {
                $lines[] = sprintf(
                    self::API_SINGLE_FORMAT,
                    $api->getVendor(),
                    $api->getName(),
                    $api->getVersion(),
                    $api->getIdentifier()
                );
            }
        }

        $this->output->writeln($lines);
    }

    /**
     * @param array $variables
     * @codeCoverageIgnore
     */
    public function assign(array $variables): void
    {}
}