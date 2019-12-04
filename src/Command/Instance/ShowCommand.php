<?php

namespace DWenzel\DataCollector\Command\Instance;

use DWenzel\DataCollector\Command\Instance;
use DWenzel\DataCollector\Configuration\Argument\IdentifierArgument;
use DWenzel\DataCollector\Service\InstanceManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
class ShowCommand extends Instance\AbstractInstanceCommand
{
    const COMMAND_DESCRIPTION = 'Show an instance.';
    const COMMAND_HELP = 'Display details for an instance.';
    const DEFAULT_COMMAND_NAME = 'data-collector:instance:show';

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        IdentifierArgument::class
    ];

    const ERROR_TEMPLATE = <<<EOT
 <error>%s</error>
EOT;

    const INSTANCE_SHOW_MESSAGE = <<<ISM

<info>identifier (uuid)</info>:  %s
<info>id (local)</info>:         %s
<info>name</info>:               %s
<info>role</info>:               %s    
ISM;

    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    const API_HEADER = <<<AHT

Registered APIs:
AHT;

    const API_SINGLE_FORMAT = <<<ASF
    
<info>vendor</info>     : %s
<info>name</info>       : %s
<info>version</info>    : %s
<info>identifier</info> : %s
ASF;


    public function __construct(
        InstanceManagerInterface $instanceManager
    )
    {
        parent::__construct();
        $this->instanceManager = $instanceManager;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $demand = $this->createDemandFromInput($input);

        $messages = [];
        try {

            /** @var \DWenzel\DataCollector\Entity\Instance $instance */
            $instance = $this->instanceManager->get($demand);
            $messages[] = sprintf(
                static::INSTANCE_SHOW_MESSAGE,
                $instance->getUuid(),
                $instance->getId(),
                $instance->getName(),
                $instance->getRole()
            );
            $apis = $instance->getApis();
            if (!$apis->isEmpty()) {
                $messages[] = static::API_HEADER;
                foreach ($apis as $api) {
                    $messages[] = sprintf(
                        self::API_SINGLE_FORMAT,
                        $api->getVendor(),
                        $api->getName(),
                        $api->getVersion(),
                        $api->getIdentifier()
                    );
                }
            }
        } catch (\Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

}
