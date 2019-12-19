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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use DWenzel\DataCollector\Command\Scheduler\ListCommand;
use DWenzel\DataCollector\Repository\ApiRepository;
use JMose\CommandSchedulerBundle\Entity\Repository\ScheduledCommandRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommandTest extends TestCase
{
    /**
     * @var ListCommand
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

        $this->subject = new ListCommand($this->managerRegistry, self::MANAGER_NAME);
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
        $output = $this->getMockBuilder(OutputInterface::class)
            ->onlyMethods(['writeln'])
            ->getMockForAbstractClass();
        /** @var InputInterface|MockObject $input */
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();

        $messageFromException = 'oops';
        $exception = new \Exception($messageFromException);
        $expectedMessages = [
            sprintf(ListCommand::ERROR_TEMPLATE, $messageFromException)
        ];

        $this->commandRepository->expects($this->any())
            ->method($this->anything())
            ->willThrowException($exception);

        $output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($input, $output);
    }
}
