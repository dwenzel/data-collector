<?php

namespace DWenzel\DataCollector\Tests\Unit\Queue;

use DWenzel\DataCollector\Service\Queue\CollectorQueue;
use DWenzel\DataCollector\Service\Queue\Storage\StorageInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
 * Class CollectorQueueTest
 */
class CollectorQueueTest extends TestCase
{
    /**
     * @var CollectorQueue
     */
    protected $subject;

    /**
     * @var StorageInterface|MockObject
     */
    private $storage;

    public function setUp(): void
    {
        $this->storage = $this->getMockForAbstractClass(StorageInterface::class);

        $this->subject = new CollectorQueue($this->storage);
    }

    public function testGetNameInitiallyGetsDefaultName()
    {
        $this->assertSame(
            CollectorQueue::DEFAULT_NAME,
            $this->subject->getName()
        );
    }

    public function testCountReturnsStorageCount()
    {
        $expectedCount = 87;

        $this->storage->expects($this->once())
            ->method('count')
            ->willReturn($expectedCount);
        $this->assertSame(
            $expectedCount,
            $this->subject->count()
        );
    }

    public function testPushAppendsToStorage()
    {
        $value = 'foo';
        $this->storage->expects($this->once())
            ->method('append')
            ->with(
                CollectorQueue::DEFAULT_NAME,
                $value
            );
        $this->subject->push($value);
    }

    public function testPopGetsFirstFromPipeline()
    {
        $valueFromStorage = 'bar';
        $this->storage->expects($this->once())
            ->method('first')
            ->with(CollectorQueue::DEFAULT_NAME)
            ->willReturn($valueFromStorage);

        $this->assertSame(
            $valueFromStorage,
            $this->subject->pop()
        );
    }
}