<?php

namespace DWenzel\DataCollector\Command\Api;

use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Command\RegisterArgumentsTrait;
use DWenzel\DataCollector\Command\RegisterOptionsTrait;
use DWenzel\DataCollector\Configuration\Argument\NameArgument;
use DWenzel\DataCollector\Configuration\Argument\Role;
use DWenzel\DataCollector\Configuration\Option\IdentifierOption;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Factory\Dto\ApiDemandFactory;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Service\ApiManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Helper\Table;
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
class ListCommand extends AbstractCommand
{
    const COMMAND_DESCRIPTION = 'List APIs';
    const COMMAND_HELP = 'List all registered APIs.';
    const DEFAULT_COMMAND_NAME = 'data-collector:api:list';
    const LIST_HEADERS = ['id', 'Vendor', 'Name', 'Version', 'Identifier'];

    const ARGUMENTS = [];
    const OPTIONS = [];

    /**
     * @var ApiRepository
     */
    protected $apiRepository;

    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    public function __construct(ApiRepository $ApiRepository)
    {
        parent::__construct();
        $this->apiRepository = $ApiRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = [];
        try {
            $apis = $this->apiRepository->findAll();
            $table = new Table($output);
            $table->setHeaders(
                static::LIST_HEADERS
            );
            foreach ($apis as $api) {
                $table->addRow(
                    [
                        $api->getId(),
                        $api->getVendor(),
                        $api->getName(),
                        $api->getVersion(),
                        $api->getIdentifier()
                    ]
                );
            }
            $table->render();
        } catch (Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

}
