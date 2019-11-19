<?php

namespace DWenzel\DataCollector\Command;

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
class ForgetInstanceCommand extends Instance\AbstractInstanceCommand
{
    const COMMAND_DESCRIPTION = 'Removes an instance.';
    const COMMAND_HELP = 'After removal of an instance its data are not collected any longer.
    Provide a UUID (universal unique identifier) and optionally force execution.';
    const DEFAULT_COMMAND_NAME = 'data-collector:instance:forget';

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        IdentifierArgument::class
    ];

    const ERROR_TEMPLATE = <<<EOT
 <error>%s</error>
EOT;

    const INSTANCE_REMOVED_MESSAGE = <<<IRM
Instance has been forgotten:
   <info>UUID</info>:   %s

   <info>You may re-add it using its UUID</info>.    
IRM;


    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    public function __construct(string $name = null, InstanceManagerInterface $instanceManager)
    {
        parent::__construct($name);
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
        // validate Arguments

        $messages = [];
        try {
            $this->instanceManager->forget($demand);
            $messages[] = sprintf(
                static::INSTANCE_REMOVED_MESSAGE,
                $demand->getIdentifier()
            );
        } catch (\Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

}
