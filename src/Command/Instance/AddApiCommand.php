<?php

namespace DWenzel\DataCollector\Command\Instance;

use DWenzel\DataCollector\Command\Instance;
use DWenzel\DataCollector\Configuration\Argument\ApiIdentifierArgument;
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
class AddApiCommand extends Instance\AbstractInstanceCommand
{
    const COMMAND_DESCRIPTION = 'Adds an API to an instance.';
    const COMMAND_HELP = 'If an API is added to an instance the instance is
     expected to implement this API and the Data Collector gathers data using the API endpoints.
     Instance and API must exist beforehand.';
    const DEFAULT_COMMAND_NAME = 'data-collector:instance:add-api';

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        IdentifierArgument::class,
        ApiIdentifierArgument::class
    ];

    const API_ADDED_MESSAGE = <<<AAM
   <info>Added API %s to instance %s.</info>
AAM;

    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

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
            // add api
        } catch (\Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

}
