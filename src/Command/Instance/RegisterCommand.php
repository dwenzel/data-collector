<?php

namespace DWenzel\DataCollector\Command\Instance;

use DWenzel\DataCollector\Command\RegisterArgumentsTrait;
use DWenzel\DataCollector\Command\RegisterOptionsTrait;
use DWenzel\DataCollector\Configuration\Argument\InstanceNameArgument;
use DWenzel\DataCollector\Configuration\Argument\Role;
use DWenzel\DataCollector\Configuration\Option\IdentifierOption;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Factory\Dto\InstanceDemandFactory;
use DWenzel\DataCollector\Service\Persistence\InstanceManagerInterface;
use Symfony\Component\Console\Command\Command;
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
class RegisterCommand extends Command
{
    use RegisterArgumentsTrait, RegisterOptionsTrait;

    const COMMAND_DESCRIPTION = 'Registers an application instance from which data should be collected';
    const COMMAND_HELP = 'An instance must be registered before data can be collected.
    Provide at least a name and optionally a UUID (universal unique identifier) and role.';
    const DEFAULT_COMMAND_NAME = 'data-collector:instance:register';

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        InstanceNameArgument::class,
        Role::class
    ];

    const OPTIONS = [
        IdentifierOption::class,
    ];
    const ERROR_TEMPLATE = <<<EOT
 <error>%s</error>
EOT;

    const INSTANT_REGISTERED_MESSAGE = <<<IRM
Instance has been registered successfully:
   <info>UUID</info>:   %s
   <info>Name</info>:   %s
   <info>Role</info>:   %s

   <info>Make sure to keep the UUID for reference</info>.    
IRM;


    protected static $defaultName = self::DEFAULT_COMMAND_NAME;
    /**
     * @var InstanceManagerInterface
     */
    protected $instanceManager;

    public function __construct(string $name = null, InstanceManagerInterface $instanceManager)
    {
        parent::__construct($name);
        $this->instanceManager = $instanceManager;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_HELP)
            ->registerArguments()
            ->registerOptions();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArguments()? $input->getArguments(): [];
        $options = $input->getOptions()? $input->getOptions(): [];

        $settings = array_merge($arguments, $options);
        $demand = InstanceDemandFactory::fromSettings($settings);
        // validate Arguments

        $messages = [];
        try {
            $instance = $this->instanceManager->register($demand);
            $messages[] = sprintf(
                static::INSTANT_REGISTERED_MESSAGE,
                $instance->getUuid(),
                $instance->getName(),
                $instance->getRole()
            );
        } catch (InvalidUuidException $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

    /**
     * Returns a list of argument classes
     *
     * @return iterable
     */
    protected function getArguments(): iterable
    {
        return static::ARGUMENTS;
    }

    /**
     * Returns a list of option classes
     *
     * @return iterable
     */
    protected function getOptions(): iterable
    {
        return static::OPTIONS;
    }
}
