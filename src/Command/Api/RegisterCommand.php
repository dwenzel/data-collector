<?php

namespace DWenzel\DataCollector\Command\Api;

use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Configuration\Argument\ApiNameArgument;
use DWenzel\DataCollector\Configuration\Argument\VendorArgument;
use DWenzel\DataCollector\Configuration\Argument\VersionArgument;
use DWenzel\DataCollector\Configuration\Option\IdentifierOption;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Factory\Dto\ApiDemandFactory;
use DWenzel\DataCollector\Service\Persistence\ApiManagerInterface;
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
class RegisterCommand extends AbstractApiCommand
{
    const COMMAND_DESCRIPTION = 'Registers an application api from which data should be collected';
    const COMMAND_HELP = 'An api must be registered before data can be collected.
    Provide a vendor name, name and version. Optionally provide an identifier (universal unique identifier) and description.';
    const DEFAULT_COMMAND_NAME = 'data-collector:api:register';

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        ApiNameArgument::class,
        VendorArgument::class,
        VersionArgument::class
    ];

    const OPTIONS = [
        IdentifierOption::class,
    ];

    const API_REGISTERED_MESSAGE = <<<ARM
Api has been registered successfully:
   <info>Name</info>:   %s
   <info>Vendor</info>:   %s
   <info>Version</info>:   %s
   <info>Identifier</info>:   %s

   <info>Make sure to keep the identifier (UUID) for reference</info>.    
ARM;


    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settings = $this->getSettingsFromInput($input);
        $demand = ApiDemandFactory::fromSettings($settings);

        // validate Arguments

        $messages = [];
        try {
            /** @var Api $api */
            $api = $this->apiManager->register($demand);
            $messages[] = sprintf(
                static::API_REGISTERED_MESSAGE,
                $api->getName(),
                $api->getVendor(),
                $api->getVersion(),
                $api->getIdentifier()
            );
        } catch (InvalidUuidException $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }
}
