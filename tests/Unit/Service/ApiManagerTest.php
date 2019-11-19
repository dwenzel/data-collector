<?php

namespace DWenzel\DataCollector\Tests\Unit\Service;

use DWenzel\DataCollector\Entity\Dto\ApiDemand;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Exception\InvalidUuidException;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Service\ApiManager;
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
 * Class ApiManagerTest
 */
class ApiManagerTest extends TestCase
{
    /**
     * @var ApiManager
     */
    protected $subject;

    /**
     * @var ApiRepository|MockObject
     */
    protected $apiRepository;

    public function setUp(): void
    {
        $this->apiRepository = $this->getMockBuilder(ApiRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['count', 'add', 'remove', 'findOneBy'])
            ->getMock();
        $this->subject = new ApiManager($this->apiRepository);
    }

    public function testHasReturnsFalseForNonExistingApi()
    {
        $uuid = '12345';
        $criteria = ['identifier' => $uuid];
        $this->apiRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(0);

        $this->assertFalse(
            $this->subject->has($uuid)
        );
    }

    /**
     * @throws InvalidUuidException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testRegisterThrowsExceptionForExistingApi()
    {
        $existingUuid = 'foo';
        $criteria = ['identifier' => $existingUuid];
        $demand = new ApiDemand();
        $demand->setIdentifier($existingUuid);

        $this->apiRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(1);

        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionCode(1574174591);

        $this->subject->register($demand);
    }

    /**
     * @throws InvalidUuidException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testRegisterGeneratesUuidIfIdentifierIsEmpty()
    {
        $demand = new ApiDemand();

        $this->apiRepository->expects($this->once())
            ->method('add')
            ->with(
                $this->isInstanceOf(Api::class)
            );

        $this->subject->register($demand);
    }

    /**
     * @throws InvalidUuidException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function testForgetRemovesApiFromRepository()
    {
        $uuid = Uuid::uuid4()->toString();
        $demand = new ApiDemand();
        $demand->setIdentifier($uuid);

        $api = new Api();

        $criteria = ['identifier' => $uuid];

        $this->apiRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(1);
        $this->apiRepository->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->willReturn($api);

        $this->apiRepository->expects($this->once())
            ->method('remove')
            ->with($api);

        $this->subject->forget($demand);

    }

    /**
     * @throws InvalidUuidException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function testForgetThrowsExceptionForMisssingApi()
    {
        $this->expectExceptionCode(1574175113);
        $uuid = Uuid::uuid4()->toString();
        $demand = new ApiDemand();
        $demand->setIdentifier($uuid);

        $criteria = ['identifier' => $uuid];

        $this->apiRepository->expects($this->once())
            ->method('count')
            ->with($criteria)
            ->willReturn(0);

        $this->subject->forget($demand);

    }

}
