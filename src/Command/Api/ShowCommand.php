<?php

namespace DWenzel\DataCollector\Command\Api;

use DWenzel\DataCollector\Configuration\Argument\IdentifierArgument;
use DWenzel\DataCollector\Entity\Api;
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
class ShowCommand extends AbstractApiCommand
{
    const COMMAND_DESCRIPTION = 'Show an API.';
    const COMMAND_HELP = 'Display details for an API.';
    const DEFAULT_COMMAND_NAME = 'data-collector:api:show';

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        IdentifierArgument::class
    ];

    const API_SHOW_MESSAGE = <<<ISM
   <info>identifier (uuid)</info>:  %s
   <info>id (local)</info>:         %s
   <info>vendor</info>:             %s
   <info>name</info>:               %s
   <info>version</info>:            %s    
   <info>description</info>:        %s    
ISM;

    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

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

            /** @var Api $instance */
            $instance = $this->apiManager->get($demand);
            $messages[] = sprintf(
                static::API_SHOW_MESSAGE,
                $instance->getIdentifier(),
                $instance->getId(),
                $instance->getVendor(),
                $instance->getName(),
                $instance->getVersion(),
                $instance->getDescription()
            );
        } catch (\Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

}
