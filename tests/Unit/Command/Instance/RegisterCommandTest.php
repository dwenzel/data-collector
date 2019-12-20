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

use DWenzel\DataCollector\Command\Instance\RegisterCommand;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Service\Persistence\InstanceManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RegisterCommandTest
 * @coversDefaultClass \DWenzel\DataCollector\Command\Instance\RegisterCommand
 */
class RegisterCommandTest extends TestCase
{
    /**
     * @var RegisterCommand
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
        $this->subject = new RegisterCommand(null, $this->instanceManager);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorSetsDescription()
    {
        $this->assertSame(
            RegisterCommand::COMMAND_DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorSetsHelp()
    {
        $this->assertSame(
            RegisterCommand::COMMAND_HELP,
            $this->subject->getHelp()
        );
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorRegistersArguments()
    {
        $argumentClasses = RegisterCommand::ARGUMENTS;

        foreach ($argumentClasses as $class) {
            if (defined($class . '::NAME')) {
                $name = $class::NAME;
                $this->assertTrue(
                    $this->subject->getDefinition()->hasArgument($name)
                );
            }
        }

    }

    /**
     * @covers ::__construct
     */
    public function testConstructorRegistersOptions()
    {
        $optionClasses = RegisterCommand::OPTIONS;

        foreach ($optionClasses as $class) {
            if (defined($class . '::NAME')) {
                $name = $class::NAME;
                $this->assertTrue(
                    $this->subject->getDefinition()->hasOption($name)
                );
            }
        }
    }

    public function testRunReturnsMessageOnSuccess()
    {
        $uuid = 'foo';
        $name = 'bar';
        $role = 'baz';

        $arguments = [
            'name' => $name,
            'role' => $role
        ];
        $instance = new Instance();
        $instance->setUuid($uuid)
            ->setRole($role)
            ->setName($name);

        $expectedMessages = [
            sprintf(RegisterCommand::INSTANT_REGISTERED_MESSAGE,
                $uuid,
                $name,
                $role
            )
        ];
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();
        $input->expects($this->atLeastOnce())
            ->method('getArguments')
            ->willReturn($arguments);
        $output = $this->createMock(OutputInterface::class);

        $this->instanceManager->expects($this->once())
            ->method('register')
            ->willReturn($instance);

        $output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($input, $output);
    }

    public function testRunReturnsErrorMessageFromException()
    {

        $uuid = 'boom';

        $options = [
            'identifier' => $uuid,
        ];

        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();
        $input->expects($this->atLeastOnce())
            ->method('getOptions')
            ->willReturn($options);
        $output = $this->createMock(OutputInterface::class);

        $exceptionMessage = 'bar';
        $exception = new InvalidUuidException($exceptionMessage);
        $this->instanceManager->expects($this->once())
            ->method('register')
            ->willThrowException($exception);

        $expectedMessages = [
          sprintf(
              RegisterCommand::ERROR_TEMPLATE,
              $exceptionMessage
          )
        ];
        $output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($input, $output);
    }
}
