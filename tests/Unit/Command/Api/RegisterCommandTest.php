<?php

namespace DWenzel\DataCollector\Tests\Unit\Command\Api;

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

use DWenzel\DataCollector\Command\Api\RegisterCommand;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Service\ApiManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RegisterApiCommandTest
 */
class RegisterCommandTest extends TestCase
{
    /**
     * @var RegisterCommand
     */
    protected $subject;

    /**
     * @var ApiManagerInterface|MockObject
     */
    protected $apiManager;

    public function setUp(): void
    {
        $this->apiManager = $this->getMockBuilder(ApiManagerInterface::class)
            ->getMockForAbstractClass();
        $this->subject = new RegisterCommand(null, $this->apiManager);
    }

    public function testConstructorSetsDescription()
    {
        $this->assertSame(
            RegisterCommand::COMMAND_DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testConstructorSetsHelp()
    {
        $this->assertSame(
            RegisterCommand::COMMAND_HELP,
            $this->subject->getHelp()
        );
    }

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
        $identifier = 'foo';
        $name = 'bar';
        $version = '1.0.0';
        $vendor = 'OOO';

        $arguments = [
            'name' => $name,
            'role' => $version
        ];
        $api = new Api();
        $api->setIdentifier($identifier)
            ->setName($name)
            ->setVersion($version)
            ->setVendor($vendor);

        $expectedMessages = [
            sprintf(RegisterCommand::API_REGISTERED_MESSAGE,
                $name,
                $vendor,
                $version,
                $identifier
            )
        ];
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();
        $input->expects($this->atLeastOnce())
            ->method('getArguments')
            ->willReturn($arguments);
        $output = $this->createMock(OutputInterface::class);

        $this->apiManager->expects($this->once())
            ->method('register')
            ->willReturn($api);

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
        $this->apiManager->expects($this->once())
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
