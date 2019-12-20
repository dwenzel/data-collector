<?php
declare(strict_types=1);
namespace DWenzel\DataCollector\Tests\Unit\Command\Scheduler;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
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

use Doctrine\ORM\EntityManagerInterface;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Command;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\CronExpression;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\ExecuteImmediately;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Name;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Priority;
use DWenzel\DataCollector\Configuration\Option\DisabledOption;
use DWenzel\DataCollector\Configuration\Option\NoOutputOption;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use DWenzel\DataCollector\Command\Scheduler\AddCommand;
use DWenzel\DataCollector\Repository\ApiRepository;
use JMose\CommandSchedulerBundle\Entity\Repository\ScheduledCommandRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommandTest extends TestCase
{
    /**
     * @var AddCommand
     */
    protected $subject;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var EntityManagerInterface|MockObject
     */
    protected $entityManager;

    /**
     * @var ApiRepository|MockObject
     */
    protected $commandRepository;

    const MANAGER_NAME = 'default';

    const LOG_PATH = 'booFar';

    public function setUp(): void
    {
        $this->managerRegistry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMockForAbstractClass();

        $this->commandRepository = $this->getMockBuilder(ScheduledCommandRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->managerRegistry->method('getManager')
            ->willReturn($this->entityManager);
        $this->entityManager->method('getRepository')
            ->willReturn($this->commandRepository);

        $this->subject = new AddCommand(
            $this->managerRegistry,
            self::MANAGER_NAME,
            self::LOG_PATH
        );
    }

    public function testConstructorSetsDescription(): void
    {
        $this->assertSame(
            AddCommand::COMMAND_DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testConstructorSetsHelp(): void
    {
        $this->assertSame(
            AddCommand::COMMAND_HELP,
            $this->subject->getHelp()
        );
    }

    public function testConstructorRegistersArguments(): void
    {
        $argumentClasses = AddCommand::ARGUMENTS;

        if (empty($argumentClasses)) {
            $this->assertEmpty(
                $this->subject->getDefinition()->getArguments()
            );
        }

        foreach ($argumentClasses as $class) {
            if (defined($class . '::NAME')) {
                $name = $class::NAME;
                $this->assertTrue(
                    $this->subject->getDefinition()->hasArgument($name)
                );
            }
        }


    }

    public function testConstructorRegistersOptions(): void
    {
        $optionClasses = AddCommand::OPTIONS;

        if (empty($optionClasses)) {
            $this->assertEmpty(
                $this->subject->getDefinition()->getOptions()
            );
        }

        foreach ($optionClasses as $class) {
            if (defined($class . '::NAME')) {
                $name = $class::NAME;
                $this->assertTrue(
                    $this->subject->getDefinition()->hasOption($name)
                );
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function testRunWritesMessageFromException():void
    {
        $arguments = [
            Name::NAME => 'fooName',
            CronExpression::NAME => '@daily',
            Command::NAME => 'lalala:command',
            Priority::NAME => Priority::DEFAULT,
            ExecuteImmediately::NAME => ExecuteImmediately::DEFAULT
        ];

        $options = [
            DisabledOption::NAME => DisabledOption::DEFAULT
        ];


        /** @var OutputInterface|MockObject $output */
        $output = $this->getMockBuilder(OutputInterface::class)
            ->onlyMethods(['writeln'])
            ->getMockForAbstractClass();
        /** @var InputInterface|MockObject $input */
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();

        $input->expects($this->once())
            ->method('getArguments')
            ->willReturn($arguments);
        $input->expects($this->once())
            ->method('getOptions')
            ->willReturn($options);

        $messageFromException = 'oops';
        $exception = new \Exception($messageFromException);
        $expectedMessages = [
            sprintf(AddCommand::ERROR_TEMPLATE, $messageFromException)
        ];

        $this->entityManager->expects($this->any())
            ->method($this->anything())
            ->willThrowException($exception);

        $output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($input, $output);
    }

    public function testRunSetsVerbosityToQuietWhenOptionNoOutputIsTrue()
    {
        $arguments = [
            Name::NAME => 'fooName',
            CronExpression::NAME => '@daily',
            Command::NAME => 'lalala:command',
            Priority::NAME => Priority::DEFAULT,
            ExecuteImmediately::NAME => ExecuteImmediately::DEFAULT
        ];

        $options = [
            DisabledOption::NAME => DisabledOption::DEFAULT,
            NoOutputOption::NAME => true
        ];


        /** @var OutputInterface|MockObject $output */
        $output = $this->getMockBuilder(OutputInterface::class)
            ->getMockForAbstractClass();
        /** @var InputInterface|MockObject $input */
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();

        $input->expects($this->once())
            ->method('getArguments')
            ->willReturn($arguments);
        $input->expects($this->once())
            ->method('getOptions')
            ->willReturn($options);

        $output->expects($this->once())
            ->method('setVerbosity')
            ->with(OutputInterface::VERBOSITY_QUIET);

        $this->subject->run($input, $output);
    }
}
