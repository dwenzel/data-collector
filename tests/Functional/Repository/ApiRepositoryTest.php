<?php

namespace DWenzel\DataCollector\Tests\Functional\Repository;

use Doctrine\ORM\EntityManager;
use DWenzel\DataCollector\DataFixtures\DataCollectorFixtures;
use DWenzel\DataCollector\Entity\Api;
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
 * Class ApiRepositoryTest
 */
class ApiRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ApiRepository
     */
    private $apiRepository;


    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->apiRepository = $this->entityManager->getRepository(Api::class);
    }

    public function testSearchByIdentifierReturnsExistingApi()
    {
        $id = DataCollectorFixtures::APIS[0]['identifier'];
        $expectedName = DataCollectorFixtures::APIS[0]['name'];

        /** @var Api $api */
        $api = $this->apiRepository
            ->findOneBy(['identifier' => $id])
        ;

        $this->assertInstanceOf(
            Api::class,
            $api
        );
        $this->assertSame($id, $api->getIdentifier());
        $this->assertSame($expectedName, $api->getName());
    }

    public function testApiCanBePersisted()
    {
        $identifier = Uuid::uuid4()->toString();
        $name = 'SomeUnexpectedName' . $identifier;
        $version = '16.0.3';
        $vendor = 'FooBar';
        $api = new Api();
        $api->setName($name)
            ->setVendor($vendor)
            ->setVersion($version)
            ->setIdentifier($identifier);
        $this->apiRepository->add($api);

        $objectFromRepository = $this->apiRepository->findOneBy(
            ['identifier' => $identifier]
        );
        $this->assertSame(
            $name,
            $objectFromRepository->getName()
        );
    }

    public function testApiCanBeRemoved()
    {
        $identifier = DataCollectorFixtures::APIS[0]['identifier'];
        $criteria = ['identifier' => $identifier];

        $api = $this->apiRepository->findOneBy($criteria);
        $this->assertInstanceOf(
            Api::class,
            $api
            );

        $this->apiRepository->remove($api);

        $this->assertNull(
            $this->apiRepository->findOneBy(
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