<?php

namespace DWenzel\DataCollector\Tests\Unit\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Factory\CollectorQueueFactory;
use DWenzel\DataCollector\Repository\InstanceRepository;
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
 * Class CollectorQueueFactoryTest
 */
class CollectorQueueFactoryTest extends TestCase
{
    /**
     * @var ManagerRegistry|MockObject
     */
    private $managerRegistry;

    /**
     * @var StorageInterface|MockObject
     */
    private $storage;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var InstanceRepository|MockObject
     */
    private $instanceRepository;

    /**
     * @var Instance|MockObject
     */
    private $instance;

    /**
     * @var string
     */
    private $managerName = 'default';

    public function setUp(): void
    {
        $this->storage = $this->getMockForAbstractClass(StorageInterface::class);
        $this->instanceRepository = $this->getMockBuilder(InstanceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->instance = $this->createMock(Instance::class);
        $this->instance->method('getUrls')
            ->willReturn(new ArrayCollection());

        $this->instanceRepository->method('findBy')
            ->willReturn([$this->instance]);
    }

    public function testCreateReturnsInstanceOfQueueInterface(): void
    {
        $this->assertInstanceOf(
            CollectorQueue::class,
            CollectorQueueFactory::create(
                $this->instanceRepository,
                $this->storage
            )
        );
    }

}
