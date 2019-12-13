<?php

namespace DWenzel\DataCollector\Tests\Unit\Service\Persistence\Backend;

use DWenzel\DataCollector\Message\Error;
use DWenzel\DataCollector\Message\Success;
use DWenzel\DataCollector\Service\Dto\ResultInterface;
use DWenzel\DataCollector\Service\Persistence\Backend\InfluxDBBackend;
use InfluxDB\Client;
use InfluxDB\Database;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

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
 * Class InfluxDBBackendTest
 */
class InfluxDBBackendTest extends TestCase
{
    /**
     * @var InfluxDBBackend
     */
    protected $subject;

    /**
     * @var Client|MockObject
     */
    protected $client;

    /**
     * @var ContainerBagInterface|MockObject
     */
    protected $containerBag;

    public function setUp(): void
    {
        $this->client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->containerBag = $this->getMockBuilder(ContainerBagInterface::class)
            ->getMockForAbstractClass();

        $this->subject = new InfluxDBBackend($this->containerBag, $this->client);
    }

    public function testWriteReturnsResult()
    {
        $parameters = [];
        $payload = [];

        $this->assertInstanceOf(
            ResultInterface::class,
            $this->subject->write($parameters, $payload)
        );
    }

    public function testWriteAddsMessageOnFailure()
    {
        $parameters = [];
        $payload = [];

        $this->client->expects($this->once())
            ->method('write')
            ->willReturn(false);
        $result = $this->subject->write($parameters, $payload);
        $messages = $result->getMessages();

        $expectedMessage = $messages[0];
        $this->assertInstanceOf(
            Error::class,
            $expectedMessage
        );

        $this->assertSame(
            1576223848,
            $expectedMessage->getIdentifier()
        );
        $this->assertSame(
            InfluxDBBackend::ERROR_WRITE_FAILED,
            $expectedMessage->getText()
        );
    }

    public function testWriteAddsMessageOnSuccess()
    {
        $parameters = [];
        $payload = [];

        $this->client->expects($this->once())
            ->method('write')
            ->willReturn(true);
        $result = $this->subject->write($parameters, $payload);
        $messages = $result->getMessages();
        $expectedMessage = $messages[0];

        $this->assertInstanceOf(
            Success::class,
            $expectedMessage
        );

        $this->assertSame(
            1576224361,
            $expectedMessage->getIdentifier()
        );
        $this->assertSame(
            InfluxDBBackend::MESSAGE_WRITE_SUCCESS,
            $expectedMessage->getText()
        );

    }

    public function testConstructorBuildsClientWithParamsFromContainerBag()
    {
        $this->containerBag->expects($this->atLeast(5))
            ->method('get')
            ->withConsecutive(
                ['data-collector.storage.influxdb.host'],
                ['data-collector.storage.influxdb.port'],
                ['data-collector.storage.influxdb.user'],
                ['data-collector.storage.influxdb.password'],
                ['data-collector.storage.influxdb.use-ssl']
            );

        $this->subject = new InfluxDBBackend($this->containerBag);
    }

    public function testListDatabasesReturnsDatabasesFromClient()
    {
        $result = ['foo'];

        $this->client->expects($this->once())
            ->method('listDatabases')
            ->willReturn($result);

        $this->assertSame(
            $result,
            $this->subject->listDatabases()
        );
    }

    public function testSelectDatabaseReturnsClientsSelction()
    {
        $name = 'bar';

        $dataBaseFromClient = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->client->expects($this->once())
            ->method('selectDB')
            ->willReturn($dataBaseFromClient);

        $this->assertSame(
            $dataBaseFromClient,
            $this->subject->selectDatabase($name)
        );
    }
}