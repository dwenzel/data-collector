<?php

namespace DWenzel\DataCollector\Command\Scheduler;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Command;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\CronExpression;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Disabled;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\ExecuteImmediately;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Name;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Priority;
use DWenzel\DataCollector\Configuration\Option\ArgumentsOption;
use DWenzel\DataCollector\Configuration\Option\DisabledOption;
use DWenzel\DataCollector\Configuration\Option\NoOutputOption;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddCommand
 */
class AddCommand extends AbstractCommand
{
    const COMMAND_DESCRIPTION = 'Adds a command to scheduler';
    const COMMAND_HELP = 'Add an new scheduled command.';
    const DEFAULT_COMMAND_NAME = 'data-collector:scheduler:add';
    const ARGUMENTS = [
        Name::class,
        CronExpression::class,
        Command::class,
        Priority::class,
        ExecuteImmediately::class
    ];
    const OPTIONS = [
        ArgumentsOption::class,
        DisabledOption::class,
        NoOutputOption::class
    ];

    protected static $defaultName = self::DEFAULT_COMMAND_NAME;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $logPath;

    /**
     * @var integer
     */
    private $commandsVerbosity;

    /**
     * AddCommand constructor.
     * @param ManagerRegistry $managerRegistry
     * @param $managerName
     * @param $logPath
     */
    public function __construct(ManagerRegistry $managerRegistry, $managerName, $logPath)
    {
        $this->entityManager = $managerRegistry->getManager($managerName);
        $this->logPath = $logPath;

        // If log path is not set to false, append the directory separator to it
        if (false !== $this->logPath) {
            $this->logPath = rtrim($this->logPath, '/\\') . DIRECTORY_SEPARATOR;
        }

        parent::__construct();
    }

    /**
     * Initialize parameters and services
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // Store the original verbosity before apply the quiet parameter
        $this->commandsVerbosity = $output->getVerbosity();

        if (true === $input->getOption('no-output')) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settings = $this->getSettingsFromInput($input);
        $command = new ScheduledCommand();

        if (!empty($settings[Name::NAME])) {
            $command->setName($settings[Name::NAME]);
        }
        if (!empty($settings[Command::NAME])) {
            $command->setCommand($settings[Command::NAME]);
        }
        if (!empty($settings[CronExpression::NAME])) {
            $command->setCronExpression($settings[CronExpression::NAME]);
        }
        $command->setPriority($settings[Priority::NAME])
            ->setExecuteImmediately($settings[ExecuteImmediately::NAME])
            ->setDisabled($settings[DisabledOption::NAME]);

        $this->entityManager->persist($command);
        $this->entityManager->flush();
    }
}
