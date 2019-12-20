<?php

namespace DWenzel\DataCollector\Command\Scheduler;

use Doctrine\ORM\EntityManager;
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
use DWenzel\DataCollector\SettingsInterface as SI;
use Exception;
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
     * @var EntityManager
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
     * @var array
     */
    private $settings = [];

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

        $this->settings = $this->getSettingsFromInput($input);

        if (isset($this->settings[NoOutputOption::NAME])
            && (true === $this->settings[NoOutputOption::NAME])) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = [];
        try {
            $command = new ScheduledCommand();

            if (!empty($this->settings[Name::NAME])) {
                $command->setName($this->settings[Name::NAME]);
            }
            if (!empty($this->settings[Command::NAME])) {
                $command->setCommand($this->settings[Command::NAME]);
            }
            if (!empty($this->settings[CronExpression::NAME])) {
                $command->setCronExpression($this->settings[CronExpression::NAME]);
            }
            $command->setPriority($this->settings[Priority::NAME])
                ->setExecuteImmediately($this->settings[ExecuteImmediately::NAME])
                ->setDisabled($this->settings[DisabledOption::NAME]);

            $this->entityManager->persist($command);
            $this->entityManager->flush();

        } catch (Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }
}
