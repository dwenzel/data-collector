<?php

namespace DWenzel\DataCollector\Tests\Unit\Service;

use DWenzel\DataCollector\Entity\Dto\InstanceDemand;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Repository\InstanceRepository;
use DWenzel\DataCollector\Service\InstanceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

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
 * Class InstanceManagerTest
 */
class InstanceManagerTest extends TestCase
{
    /**
     * @var InstanceManager
     */
    protected $subject;

    /**
     * @var InstanceRepository|MockObject
     */
    protected $instanceRepository;

    public function setUp(): void
    {
        $this->instanceRepository = $this->getMockBuilder(InstanceRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['count', 'add', 'remove', 'findOneBy'])
            ->getMock();
        $this->subject = new InstanceManager($this->instanceRepository);
    }

    public function testHasReturnsFalseForNonExistingInstance()
    {
        $uuid = '12345';
        $criteria = ['uuid' => $uuid];
        $this->instanceRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(0);

        $this->assertFalse(
            $this->subject->has($uuid)
        );
    }

    public function testRegisterThrowsExceptionForExistingInstance()
    {
        $existingUuid = 'foo';
        $criteria = ['uuid' => $existingUuid];
        $demand = new InstanceDemand();
        $demand->setIdentifier($existingUuid);

        $this->instanceRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(1);

        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionCode(1573677985);

        $this->subject->register($demand);
    }

    public function testRegisterThrowsExceptionForInvalidIdentifier()
    {
        $invalidUuid = 'foo';
        $criteria = ['uuid' => $invalidUuid];
        $demand = new InstanceDemand();
        $demand->setIdentifier($invalidUuid);

        $this->instanceRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(0);

        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionCode(1573690590);

        $this->subject->register($demand);
    }

    public function testRegisterGeneratesUuidIfIdentifierIsEmpty()
    {
        $demand = new InstanceDemand();

        $this->instanceRepository->expects($this->once())
            ->method('add')
            ->with(
                $this->isInstanceOf(Instance::class)
            );

        $this->subject->register($demand);
    }

    public function testForgetRemovesInstanceFromRepository()
    {
        $uuid = Uuid::uuid4()->toString();
        $demand = new InstanceDemand();
        $demand->setIdentifier($uuid);

        $instance = new Instance();

        $criteria = ['uuid' => $uuid];

        $this->instanceRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(1);
        $this->instanceRepository->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn($instance);

        $this->instanceRepository->expects($this->once())
            ->method('remove')
            ->with($instance);

        $this->subject->forget($demand);

    }

    public function testForgetThrowsExceptionForMisssingInstance()
    {
        $this->expectExceptionCode(1573766261);
        $uuid = Uuid::uuid4()->toString();
        $demand = new InstanceDemand();
        $demand->setIdentifier($uuid);

        $criteria = ['uuid' => $uuid];

        $this->instanceRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(0);

        $this->subject->forget($demand);

    }

}