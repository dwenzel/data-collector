<?php

namespace DWenzel\DataCollector\Command\Api;

use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Command\Instance;
use DWenzel\DataCollector\Configuration\Argument\IdentifierArgument;
use DWenzel\DataCollector\Entity\Dto\ApiDemand;
use DWenzel\DataCollector\Factory\Dto\ApiDemandFactory;
use DWenzel\DataCollector\Service\ApiManagerInterface;
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
class ForgetCommand extends AbstractCommand
{
    const COMMAND_DESCRIPTION = 'Removes a registered API.';
    const COMMAND_HELP = <<<CCC
After removal of an API, data is not collected any longer from its endpoint. 
Provide an identifier.'
CCC;
    const DEFAULT_COMMAND_NAME = 'data-collector:api:forget';

    /**
     * @var ApiManagerInterface
     */
    protected $apiManager;

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        IdentifierArgument::class
    ];

    const ERROR_TEMPLATE = <<<EOT
 <error>%s</error>
EOT;

    const API_REMOVED_MESSAGE = <<<IRM
API has been forgotten:
   <info>UUID</info>:   %s

   <info>You may re-add it using its identifier</info>.    
IRM;


    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    public function __construct(
        ApiManagerInterface $apiManager
    )
    {
        parent::__construct();
        $this->apiManager = $apiManager;
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
            $this->apiManager->forget($demand);
            $messages[] = sprintf(
                static::API_REMOVED_MESSAGE,
                $demand->getIdentifier()
            );
        } catch (\Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

    /**
     * @param InputInterface $input
     * @return ApiDemand
     */
    protected function createDemandFromInput(InputInterface $input): ApiDemand
    {
        $settings = $this->getSettingsFromInput($input);

        return ApiDemandFactory::fromSettings($settings);
    }
}
