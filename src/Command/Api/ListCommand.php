<?php

namespace DWenzel\DataCollector\Command\Api;

use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\SettingsInterface as SI;
use DWenzel\DataCollector\Traits\TableViewHelperTrait;
use Exception;
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
    use TableViewHelperTrait;

    const COMMAND_DESCRIPTION = 'List APIs';
    const COMMAND_HELP = 'List all registered APIs.';
    const DEFAULT_COMMAND_NAME = 'data-collector:api:list';
    const LIST_HEADERS = ['id', 'Vendor', 'Name', 'Version', 'Identifier'];

    const ARGUMENTS = [];
    const OPTIONS = [];
    protected static $defaultName = self::DEFAULT_COMMAND_NAME;
    /**
     * @var ApiRepository
     */
    protected $apiRepository;

    public function __construct(ApiRepository $apiRepository)
    {
        parent::__construct();
        $this->apiRepository = $apiRepository;
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

            $rows = [];
            foreach ($apis as $api) {
                $rows[] = [
                    $api->getId(),
                    $api->getVendor(),
                    $api->getName(),
                    $api->getVersion(),
                    $api->getIdentifier()
                ];
            }

            $variables = [
                SI::HEADERS_KEY => static::LIST_HEADERS,
                SI::ROWS_KEY => $rows
            ];

            $this->viewHelper->assign($variables);
            $this->viewHelper->render();

        } catch (Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
            $output->writeln($messages);
        }
    }

}
