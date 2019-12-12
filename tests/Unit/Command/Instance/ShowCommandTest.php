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

use DWenzel\DataCollector\Command\Instance\ShowCommand;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Service\Persistence\InstanceManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ForgetCommandTest
 */
class ShowCommandTest extends TestCase
{
    /**
     * @var ShowCommand
     */
    protected $subject;

    /**
     * @var InstanceManagerInterface|MockObject
     */
    protected $instanceManager;

    public function setUp(): void
    {
        $this->instanceManager = $this->getMockBuilder(InstanceManagerInterface::class)
            ->getMockForAbstractClass();
        $this->subject = new ShowCommand($this->instanceManager);
    }

    public function testConstructorSetsDescription()
    {
        $this->assertSame(
            ShowCommand::COMMAND_DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testConstructorSetsHelp()
    {
        $this->assertSame(
            ShowCommand::COMMAND_HELP,
            $this->subject->getHelp()
        );
    }

    public function testConstructorShowsArguments()
    {
        $argumentClasses = ShowCommand::ARGUMENTS;

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
        $optionClasses = ShowCommand::OPTIONS;

        if (empty($optionClasses)) {
            $this->assertEmpty(
                $this->subject->getDefinition()->getOptions()
            );
        } else {
            foreach ($optionClasses as $class) {
                if (defined($class . '::NAME')) {
                    $name = $class::NAME;
                    $this->assertTrue(
                        $this->subject->getDefinition()->hasOption($name)
                    );
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function testRunReturnsMessageOnSuccess(): void
    {
        $uuid = 'foo';
        $id = null;
        $name = 'bar';
        $role = 'baz';

        $arguments = [
            'identifier' => $uuid,
        ];
        $instance = new Instance();
        $instance->setUuid($uuid)
            ->setName($name)
            ->setRole($role);

        $expectedMessages = [
            sprintf(ShowCommand::INSTANCE_SHOW_MESSAGE,
                $uuid,
                $id,
                $name,
                $role
            )
        ];
        /** @var InputInterface|MockObject $input */
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();
        $input->expects($this->atLeastOnce())
            ->method('getArguments')
            ->willReturn($arguments);
        $output = $this->createMock(OutputInterface::class);

        $this->instanceManager->expects($this->once())
            ->method('get')
            ->willReturn($instance);

        $output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($input, $output);
    }

    /**
     * @throws \Exception
     */
    public function testRunReturnsErrorMessageFromException()
    {

        $uuid = 'boom';

        $options = [
            'identifier' => $uuid,
        ];

        /** @var InputInterface|MockObject $input */
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();
        $input->expects($this->atLeastOnce())
            ->method('getOptions')
            ->willReturn($options);
        $output = $this->createMock(OutputInterface::class);

        $exceptionMessage = 'bar';
        $exception = new \Exception($exceptionMessage);
        $this->instanceManager->expects($this->once())
            ->method('get')
            ->willThrowException($exception);

        $expectedMessages = [
            sprintf(
                ShowCommand::ERROR_TEMPLATE,
                $exceptionMessage
            )
        ];
        $output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($input, $output);
    }
}
