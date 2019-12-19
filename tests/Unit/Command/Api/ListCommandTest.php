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
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Service\Persistence\ApiManagerInterface;
use DWenzel\DataCollector\ViewHelper\ViewHelperInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DWenzel\DataCollector\SettingsInterface as SI;

/**
 * Class ListCommandTest
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
        /** @var OutputInterface|MockObject $output */
        $this->output = $this->getMockBuilder(OutputInterface::class)
            ->getMockForAbstractClass();
        /** @var InputInterface|MockObject $input */
        $this->input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();

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
        /** @var OutputInterface|MockObject $output */
        $this->output = $this->getMockBuilder(OutputInterface::class)
            ->onlyMethods(['writeln'])
            ->getMockForAbstractClass();
        $messageFromException = 'oops';
        $exception = new \Exception($messageFromException);
        $expectedMessages = [
            sprintf(ListCommand::ERROR_TEMPLATE, $messageFromException)
        ];

        $this->apiRepository->expects($this->any())
            ->method($this->anything())
            ->willThrowException($exception);

        $this->output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($this->input, $this->output);
    }

    public function testInitializeSetsViewHelper()
    {
        $this->subject = $this->getMockBuilder(ListCommand::class)
            ->onlyMethods(['setViewHelper'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject->expects($this->once())
            ->method('setViewHelper');

        $this->subject->initialize($this->input, $this->output);
    }

    /**
     * @throws \Exception
     */
    public function testRunInitializesInstance()
    {
        /** @var ListCommand|MockObject subject */
        $this->subject = $this->getMockBuilder(ListCommand::class)
            ->onlyMethods(['initialize'])
            ->setConstructorArgs([$this->apiRepository])
            ->getMock();

        $this->subject->expects($this->once())
            ->method('initialize')
            ->with($this->input, $this->output);

        $this->subject->run($this->input, $this->output);
    }

    /**
     * @throws \Exception
     */
    public function testRunAssignsVariablesToViewHelperAndRenders(): void
    {
        $api = new Api();
        $apiArray = [
            [null, null, null, null, null]
        ];

        $result = [$api];
        $viewHelper = $this->getMockForAbstractClass(ViewHelperInterface::class);
        $this->subject->setViewHelper($viewHelper);

        $this->apiRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($result);

        $expectedVariables = [
            SI::HEADERS_KEY => ListCommand::LIST_HEADERS,
            SI::ROWS_KEY => $apiArray
        ];

        $viewHelper->expects($this->once())
            ->method('assign')
            ->with($expectedVariables);

        $viewHelper->expects($this->once())
            ->method('render');

        $this->subject->run($this->input, $this->output);
    }
}
