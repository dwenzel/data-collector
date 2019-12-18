<?php

namespace DWenzel\DataCollector\Command;

use DWenzel\DataCollector\Controller\CollectController;
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
class CollectCommand extends AbstractCommand
{
    const COMMAND_DESCRIPTION = 'Collect data from instances.';
    const COMMAND_HELP = <<<CCC
    Collects data from all registered instances and pushes them to the database.
'
CCC;
    const DEFAULT_COMMAND_NAME = 'data-collector:collect';

    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    /**
     * @var CollectController
     */
    protected $controller;

    public function __construct(CollectController $controller)
    {
        parent::__construct();
        $this->controller = $controller;
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
            $this->controller->runAction();
        } catch (\Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        if((bool)$messages){
            $output->writeln($messages);
        }
    }
}
