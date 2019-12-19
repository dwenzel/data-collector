<?php

namespace DWenzel\DataCollector\Command\Scheduler;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Repository\InstanceRepository;
use Exception;
use JMose\CommandSchedulerBundle\Entity\Repository\ScheduledCommandRepository;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
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
    const COMMAND_DESCRIPTION = 'List scheduled commands';
    const COMMAND_HELP = 'List all scheduled commands.';
    const DEFAULT_COMMAND_NAME = 'data-collector:scheduler:list';
    const LIST_HEADERS = ['id', 'Name', 'Command', 'Cron Expression', 'disabled'];

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var ScheduledCommandRepository
     */
    protected $commandRepository;


    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    public function __construct(ManagerRegistry $managerRegistry, $managerName)
    {
        parent::__construct();
        $this->entityManager = $managerRegistry->getManager($managerName);
        $this->commandRepository = $this->entityManager->getRepository(ScheduledCommand::class);
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
            /** @var ScheduledCommand[] $commands */
            $commands = $this->commandRepository->findAll();
            $table = new Table($output);
            $table->setHeaders(
                static::LIST_HEADERS
            );
            foreach ($commands as $scheduledCommand) {
                $table->addRow(
                    [
                        $scheduledCommand->getId(),
                        $scheduledCommand->getName(),
                        $scheduledCommand->getCommand(),
                        $scheduledCommand->getCronExpression(),
                        $scheduledCommand->getDisabled()
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
