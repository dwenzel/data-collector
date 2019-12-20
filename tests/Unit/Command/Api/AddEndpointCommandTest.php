<?php

namespace DWenzel\DataCollector\Tests\Unit\Command\Api;

use DWenzel\DataCollector\Command\Api\AddEndpointCommand;
use DWenzel\DataCollector\Configuration\Argument\EndPointArgument;
use DWenzel\DataCollector\Configuration\Argument\IdentifierArgument;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Service\Persistence\ApiManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

/**
 * Class AddEndpointCommandTest
 */
class AddEndpointCommandTest extends TestCase
{
    /**
     * @var AddEndpointCommand
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
     * @var InputInterface|MockObject
     */
    protected $input;

    /**
     * @var OutputInterface|MockObject
     */
    protected $output;

    public function setUp(): void
    {
        $this->apiManager = $this->getMockBuilder(ApiManagerInterface::class)
            ->getMockForAbstractClass();
        $this->apiRepository = $this->getMockBuilder(ApiRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new AddEndpointCommand(
            $this->apiManager,
            $this->apiRepository
        );

        $this->input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass();
        $this->output = $this->getMockBuilder(OutputInterface::class)
            ->getMockForAbstractClass();
    }

    public function testRunThrowsExceptionForMissingApi()
    {
        $invalidApiIdentifier = 'foo';
        $endPointName = 'bar';

        $errorMessage = sprintf(AddEndpointCommand::API_NOT_FOUND_ERROR, $invalidApiIdentifier);
        $expectedMessages = [
            sprintf(AddEndpointCommand::ERROR_TEMPLATE, $errorMessage)
        ];
        $this->input->expects($this->exactly(2))
            ->method('getArgument')
            ->withConsecutive(
                [IdentifierArgument::NAME],
                [EndPointArgument::NAME]
            )
            ->willReturnOnConsecutiveCalls($invalidApiIdentifier, $endPointName);

        $this->apiRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['identifier' => $invalidApiIdentifier])
            ->willReturn(null);

        $this->output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($this->input, $this->output);
    }

    public function testRunAddsMessageOnSuccess()
    {
        $apiIdentifier = 'foo';
        $endPointName = 'bar';
        $api = $this->getMockBuilder(Api::class)
            ->getMock();

        $successMessage = sprintf(AddEndpointCommand::ENDPOINT_ADDED_MESSAGE, $endPointName, $apiIdentifier);
        $expectedMessages = [
            $successMessage
        ];
        $this->input->expects($this->exactly(2))
            ->method('getArgument')
            ->withConsecutive(
                [IdentifierArgument::NAME],
                [EndPointArgument::NAME]
            )
            ->willReturnOnConsecutiveCalls($apiIdentifier, $endPointName);


        $this->apiRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($api);

        $this->apiRepository->expects($this->once())
            ->method('update')
            ->with($api);

        $this->output->expects($this->once())
            ->method('writeln')
            ->with($expectedMessages);

        $this->subject->run($this->input, $this->output);
    }
}