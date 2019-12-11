<?php

namespace DWenzel\DataCollector\Tests\Functional\Repository;

use Doctrine\ORM\EntityManager;
use DWenzel\DataCollector\DataFixtures\DataCollectorFixtures;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\Repository\InstanceRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
 * Class InstanceRepositoryTest
 */
class InstanceRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var InstanceRepository
     */
    private $instanceRepository;


    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->instanceRepository = $this->entityManager->getRepository(Instance::class);
    }

    public function testSearchByIdentifierReturnsExistingInstance()
    {
        $id = DataCollectorFixtures::INSTANCES[0]['identifier'];
        $expectedName = DataCollectorFixtures::INSTANCES[0]['name'];

        /** @var Instance $instance */
        $instance = $this->instanceRepository
            ->findOneBy(['uuid' => $id])
        ;

        $this->assertInstanceOf(
            Instance::class,
            $instance
        );
        $this->assertSame($id, $instance->getUuid());
        $this->assertSame($expectedName, $instance->getName());
    }

    public function testInstanceCanBePersisted()
    {
        $uuid = Uuid::uuid4()->toString();
        $name = 'SomeUnexpectedName' . $uuid;
        $role = 'bar';
        $url = 'baz';

        $instance = new Instance();
        $instance->setName($name)
            ->setUuid($uuid)
            ->setRole($role)
            ->setBaseUrl($url);
        $this->instanceRepository->add($instance);

        $objectFromRepository = $this->instanceRepository->findOneBy(
            ['uuid' => $uuid]
        );
        $this->assertSame(
            $name,
            $objectFromRepository->getName()
        );
    }

    public function testInstanceCanBeRemoved()
    {
        $uuid = DataCollectorFixtures::INSTANCES[0]['identifier'];
        $criteria = ['uuid' => $uuid];

        $instance = $this->instanceRepository->findOneBy($criteria);
        $this->assertInstanceOf(
            Instance::class,
            $instance
            );

        $this->instanceRepository->remove($instance);

        $this->assertNull(
            $this->instanceRepository->findOneBy(
                $criteria
            )
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

}