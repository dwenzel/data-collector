<?php
declare(strict_types=1);
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

use DWenzel\DataCollector\Command\Api\ListCommand;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Service\Persistence\ApiManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ForgetApiCommandTest
 */
class ListCommandTest extends TestCase
{
    /**
     * @var ListCommand
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
        $this->apiRepository = $this->getMockBuilder(ApiRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = new ListCommand($this->apiRepository);
    }

    public function testConstructorSetsDescription(): void
    {
        $this->assertSame(
            ListCommand::COMMAND_DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testConstructorSetsHelp(): void
    {
        $this->assertSame(
            ListCommand::COMMAND_HELP,
            $this->subject->getHelp()
        );
    }

    public function testConstructorRegistersArguments(): void
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

    public function testConstructorRegistersOptions(): void
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
     * @throws \Exception
     */
    public function testRunWritesMessageFromException():void
    {
        $output = $this->getMockBuilder(OutputInterface::class)
            ->onlyMethods(['writeln'])
            ->getMockForAbstractClass();
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();

        $messageFromException = 'oops';
        $exception = new \Exception($messageFromException);
        $expectedMessages = [
            sprintf(ListCommand::ERROR_TEMPLATE, $messageFromException)
        ];

        $this->apiRepository->expects($this->any())
            ->method($this->anything())
            ->willThrowException($exception);

        $output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($input, $output);
    }
}
