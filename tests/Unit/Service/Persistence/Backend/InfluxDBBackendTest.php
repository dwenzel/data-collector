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
use DWenzel\DataCollector\SettingsInterface as SI;

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

    const DEFAULT_CONSTRUCTOR_PARAMETERS = [];

    public function setUp(): void
    {
        $this->client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->containerBag = $this->getMockBuilder(ContainerBagInterface::class)
            ->getMockForAbstractClass();

        $this->subject = new InfluxDBBackend(self::DEFAULT_CONSTRUCTOR_PARAMETERS, $this->client);
    }

    public function testWriteReturnsResult(): void
    {
        $parameters = [];
        $payload = [];

        $this->assertInstanceOf(
            ResultInterface::class,
            $this->subject->write($parameters, $payload)
        );
    }

    public function testWriteAddsMessageOnFailure(): void
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

    public function testWriteAddsMessageOnSuccess(): void
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

    public function testConstructorInstantiatesClient(): void
    {
        $host = 'foo.com';
        $port = 12345;
        $user = 'boom';
        $password = 't3e';
        $useSSL = false;

        $parameters = [
            SI::HOST_KEY => $host,
            SI::PORT_KEY => $port,
            SI::USER_KEY => $user,
            SI::PASSWORD_KEY => $password,
            SI::SSL_KEY => $useSSL
        ];
        $this->subject = new InfluxDBBackend($parameters);

        $this->assertInstanceOf(
            Client::class,
            $this->subject->getClient()
        );
    }

    public function testConstructorBuildsClientWithInjectedParameters(): void
    {
        $host = 'foo.com';
        $port = 12345;
        $user = 'boom';
        $password = 't3e';
        $useSSL = false;

        $parameters = [
            SI::HOST_KEY => $host,
            SI::PORT_KEY => $port,
            SI::USER_KEY => $user,
            SI::PASSWORD_KEY => $password,
            SI::SSL_KEY => $useSSL
        ];
        $this->subject = new InfluxDBBackend($parameters);

        $expectedSchema = 'http';
        $expectedBaseUri = sprintf(
            '%s://%s:%d',
            $expectedSchema,
            $host,
            $port);
        $client = $this->subject->getClient();
        $this->assertSame(
            $expectedBaseUri,
            $client->getBaseURI()
        );

        $this->assertSame(
            $host,
            $client->getHost()
        );
    }

    /**
     * @throws \InfluxDB\Exception
     */
    public function testListDatabasesReturnsDatabasesFromClient(): void
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

    public function testSelectDatabaseReturnsClientsSelction(): void
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
