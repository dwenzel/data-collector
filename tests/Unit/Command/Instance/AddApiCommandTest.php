<?php
declare(strict_types=1);

namespace DWenzel\DataCollector\Tests\Unit\Command\Instance;

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

use DWenzel\DataCollector\Command\Instance\AddApiCommand;
use DWenzel\DataCollector\Command\Scheduler\AddCommand;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Command;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\CronExpression;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\ExecuteImmediately;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Name;
use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\Priority;
use DWenzel\DataCollector\Configuration\Option\DisabledOption;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Service\Persistence\ApiManagerInterface;
use DWenzel\DataCollector\Service\Persistence\InstanceManagerInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddApiCommandTest extends TestCase
{
    /**
     * @var AddApiCommand
     */
    protected $subject;

    /**
     * @var InstanceManagerInterface|MockObject
     */
    protected $instanceManager;

    /**
     * @var ApiManagerInterface|MockObject
     */
    protected $apiManager;

    /**
     * @var InputInterface|MockObject
     */
    protected $input;

    /**
     * @var OutputInterface|MockObject
     */
    protected $output;

    /**
     * set up subject
     */
    public function setUp(): void
    {
        $this->instanceManager = $this->getMockForAbstractClass(InstanceManagerInterface::class);
        $this->apiManager = $this->getMockForAbstractClass(ApiManagerInterface::class);
        $this->input = $this->getMockForAbstractClass(InputInterface::class);
        $this->output = $this->getMockForAbstractClass(OutputInterface::class);

        $this->subject = new AddApiCommand(
            $this->instanceManager,
            $this->apiManager
        );
    }

    public function testConstructorSetsDescription(): void
    {
        $this->assertSame(
            AddApiCommand::COMMAND_DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testConstructorSetsHelp(): void
    {
        $this->assertSame(
            AddApiCommand::COMMAND_HELP,
            $this->subject->getHelp()
        );
    }

    public function testConstructorRegistersArguments(): void
    {
        $argumentClasses = AddApiCommand::ARGUMENTS;

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
        $optionClasses = AddApiCommand::OPTIONS;

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
     * @throws Exception
     */
    public function testRunWritesMessageFromException(): void
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


        $this->input->expects($this->once())
            ->method('getArguments')
            ->willReturn($arguments);
        $this->input->expects($this->once())
            ->method('getOptions')
            ->willReturn($options);

        $messageFromException = 'oops';
        $exception = new Exception($messageFromException);
        $expectedMessages = [
            sprintf(AddCommand::ERROR_TEMPLATE, $messageFromException)
        ];

        $this->instanceManager->expects($this->any())
            ->method($this->anything())
            ->willThrowException($exception);

        $this->output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($this->input, $this->output);
    }

    public function testRunUpdatesInstance()
    {
        $instance = $this->createMock(Instance::class);
        $api = $this->createMock(Api::class);
        $this->instanceManager->expects($this->once())
            ->method('get')
            ->willReturn($instance);
        $this->apiManager->expects($this->once())
            ->method('get')
            ->willReturn($api);
        $instance->expects($this->once())
            ->method('addApi')
            ->with($api);
        $this->instanceManager->expects($this->once())
            ->method('update')
            ->with($instance);

        $this->subject->run($this->input, $this->output);
    }
}
