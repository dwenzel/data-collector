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

use DWenzel\DataCollector\Command\Api\ShowCommand;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Service\ApiManagerInterface;
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
     * @var ApiManagerInterface|MockObject
     */
    protected $apiManager;

    /**
     * @var ApiRepository|MockObject
     */
    protected $apiRepository;

    public function setUp(): void
    {
        $this->apiManager = $this->getMockBuilder(ApiManagerInterface::class)
            ->getMockForAbstractClass();
        $this->apiRepository = $this->createMock(ApiRepository::class);
        $this->subject = new ShowCommand($this->apiManager, $this->apiRepository);
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
        $identifier = 'foo';
        $id = null;
        $name = 'bar';
        $version = 'baz';
        $vendor = 'boom';
        $description = 'funny';

        $arguments = [
            'identifier' => $identifier,
        ];
        $api = new Api();
        $api->setIdentifier($identifier)
            ->setName($name)
            ->setVendor($vendor)
            ->setDescription($description)
            ->setVersion($version);

        $expectedMessages = [
            sprintf(ShowCommand::API_SHOW_MESSAGE,
                $identifier,
                $id,
                $vendor,
                $name,
                $version,
                $description
            )
        ];
        /** @var InputInterface|MockObject $input */
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();
        $input->expects($this->atLeastOnce())
            ->method('getArguments')
            ->willReturn($arguments);
        $output = $this->createMock(OutputInterface::class);

        $this->apiManager->expects($this->once())
            ->method('get')
            ->willReturn($api);

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
        $this->apiManager->expects($this->once())
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
