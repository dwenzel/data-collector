<?php

namespace DWenzel\DataCollector\Tests\Unit\Queue\Storage;

use DWenzel\DataCollector\Service\Queue\Storage\RedisStorage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 Dirk Wenzel
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
 * Class RedisStorageTest
 */
class RedisStorageTest extends TestCase
{
    /**
     * @var RedisStorage
     */
    protected $subject;

    /**
     * @var ClientInterface|MockObject
     */
    private $client;

    const KEY = 'queue:bar';

    public function setUp(): void
    {
        $this->client = $this->getMockForAbstractClass(ClientInterface::class);
        $this->subject = new RedisStorage($this->client);
    }

    public function testFirstReturnsUnserializedElementFromClient()
    {

        $valueFromClient = serialize(['boo', 'baz']);
        $this->client->expects($this->once())
            ->method('pop')
            ->with(self::KEY)
            ->willReturn($valueFromClient);

        $expectedValue = unserialize($valueFromClient);
        $this->assertSame(
            $expectedValue,
            $this->subject->first(self::KEY)
        );
    }

    public function testAppendPushesElementToClient()
    {
        $value = ['boom'];
        $expectedValue = serialize($value);

        $this->client->expects($this->once())
            ->method('push')
            ->with(self::KEY, $expectedValue);

        $this->subject->append(self::KEY, $value);
    }

    public function testCountReturnsCountFromClient()
    {
        $countFromClient = 89;
        $this->client->expects($this->once())
            ->method('count')
            ->with(self::KEY)
            ->willReturn($countFromClient);

        $this->assertSame(
            $countFromClient,
            $this->subject->count(self::KEY)
        );
    }
}