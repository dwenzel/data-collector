<?php

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

use DWenzel\DataCollector\Command\Instance\ListCommand;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Repository\InstanceRepository;
use DWenzel\DataCollector\Service\Persistence\InstanceManagerInterface;
use DWenzel\DataCollector\SettingsInterface as SI;
use DWenzel\DataCollector\ViewHelper\ViewHelperInterface;
use Exception;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ForgetInstanceCommandTest
 */
class ListCommandTest extends TestCase
{
    /**
     * @var ListCommand
     */
    protected $subject;

    /**
     * @var InstanceManagerInterface|MockObject
     */
    protected $instanceManager;

    /**
     * @var InstanceRepository
     */
    protected $instanceRepository;

    /**
     * @var OutputInterface|MockObject
     */
    protected $output;

    /**
     * @var InputInterface|MockObject
     */
    protected $input;


    public function setUp(): void
    {
        $this->instanceManager = $this->getMockBuilder(InstanceManagerInterface::class)
            ->getMockForAbstractClass();
        $this->instanceRepository = $this->getMockBuilder(InstanceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = new ListCommand($this->instanceRepository);
        $this->output = $this->getMockForAbstractClass(OutputInterface::class);
        $this->input = $this->getMockForAbstractClass(InputInterface::class);
    }

    public function testConstructorSetsDescription()
    {
        $this->assertSame(
            ListCommand::COMMAND_DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testConstructorSetsHelp()
    {
        $this->assertSame(
            ListCommand::COMMAND_HELP,
            $this->subject->getHelp()
        );
    }

    public function testConstructorRegistersArguments()
    {
        $argumentClasses = ListCommand::ARGUMENTS;

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

    public function testConstructorRegistersOptions()
    {
        $optionClasses = ListCommand::OPTIONS;

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
    public function testRunWritesMessageFromException():void
    {
        $messageFromException = 'oops';
        $exception = new Exception($messageFromException);
        $expectedMessages = [
            sprintf(\DWenzel\DataCollector\Command\Scheduler\ListCommand::ERROR_TEMPLATE, $messageFromException)
        ];

        $this->instanceRepository->expects($this->any())
            ->method($this->anything())
            ->willThrowException($exception);

        $this->output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($this->input, $this->output);
    }

    /**
     * @throws Exception
     */
    public function testRunAssignsVariablesToViewHelperAndRenders(): void
    {
        $instance = new Instance();
        $instancesArray = [
            [null, null, null, Instance::ROLE_UNKNOWN]
        ];

        $result = [$instance];
        $viewHelper = $this->getMockForAbstractClass(ViewHelperInterface::class);
        $this->subject->setViewHelper($viewHelper);

        $this->instanceRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($result);

        $expectedVariables = [
            SI::HEADERS_KEY => ListCommand::LIST_HEADERS,
            SI::ROWS_KEY => $instancesArray
        ];

        $viewHelper->expects($this->once())
            ->method('assign')
            ->with($expectedVariables);

        $viewHelper->expects($this->once())
            ->method('render');

        $this->subject->run($this->input, $this->output);
    }


}
