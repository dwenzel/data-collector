<?php

namespace DWenzel\DataCollector\Tests\Unit\Service\Persistence;

use DWenzel\DataCollector\Service\Dto\PersistDemand;
use DWenzel\DataCollector\Service\Dto\ResultInterface;
use DWenzel\DataCollector\Service\Persistence\Backend\StorageBackendInterface;
use DWenzel\DataCollector\Service\Persistence\StorageService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
 * Class StorageServiceTest
 */
class StorageServiceTest extends TestCase
{
    /**
     * @var StorageService
     */
    protected $subject;

    /**
     * @var StorageBackendInterface|MockObject
     */
    protected $backend;

    public function setUp(): void
    {
        $this->backend = $this->getMockBuilder(StorageBackendInterface::class)
            ->getMockForAbstractClass();
        $this->subject = new StorageService($this->backend);
    }

    public function testPersistWritesToBackend()
    {
        $persistDemand = $this->getMockBuilder(PersistDemand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $result = $this->getMockForAbstractClass(ResultInterface::class);
        $parameter = ['foo'];
        $payload = ['bar'];

        $persistDemand->expects($this->once())
            ->method('getPayload')
            ->willReturn($payload);
        $persistDemand->expects($this->once())
            ->method('getParameter')
            ->willReturn($parameter);

        $this->backend->expects($this->once())
            ->method('write')
            ->with($parameter, $payload)
            ->willReturn($result);

        $this->assertSame(
            $result,
            $this->subject->persist($persistDemand)
        );
    }
}