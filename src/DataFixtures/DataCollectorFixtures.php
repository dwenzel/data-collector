<?php

namespace DWenzel\DataCollector\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Instance;

class DataCollectorFixtures extends Fixture
{
    const INSTANCES = [
        [
            'identifier' => '111301a-59d9-4146-9810-a1f376327f44',
            'name' => 'foo',
            'role' => 'production/staging',
            'baseUrl' => 'foo.com'
        ],
        [
            'identifier' => '2225301a-59d9-4146-9810-a1f376327fae',
            'name' => 'foo',
            'role' => 'testing',
            'baseUrl' => 'bar.org'
        ],
        [
            'identifier' => '3335301a-59d9-4146-9810-a1f376327f44',
            'name' => 'bar',
            'role' => '',
            'baseUrl' => 'foo.bar'
        ],
        [
            'identifier' => '4445301a-59d9-4146-9810-a1f376327f44',
            'name' => 'bar',
            'role' => '',
            'baseUrl' => 'bar.foo'
        ],
    ];
    const APIS = [
        [
            'identifier' => '111301a-59d9-4146-5555-a1f376327f44',
            'vendor' => 'Mimir',
            'name' => 'foo',
            'version' => '1.0.0',
            'description' => 'Seer. Tells about the future.'
        ],
        [
            'identifier' => '2225301a-59d9-4146-5555-a1f376327fae',
            'vendor' => 'Mimir',
            'name' => 'foo',
            'version' => '2.0.0',
            'description' => 'Seer. Tells about the future.'
        ],
        [
            'identifier' => '3335301a-59d9-4146-5555-a1f376327f44',
            'vendor' => 'Loki',
            'name' => 'fire',
            'version' => '1.0.0',
            'description' => ''
        ],
        [
            'identifier' => '4445301a-59d9-4146-5555-a1f376327f44',
            'vendor' => 'DWenzel',
            'name' => 'reporter',
            'version' => '1.0.0',
            'description' => 'Reporting API for application instances'
        ],
    ];

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Load data fixtures
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->createInstances();
        $this->createApis();
        $manager->flush();
    }

    /**
     * Create instance fixtures
     */
    protected function createInstances(): void
    {
        foreach (self::INSTANCES as $config) {
            $instance = new Instance();
            $instance->setUuid($config['identifier'])
                ->setRole($config['role'])
                ->setName($config['name']);

            $this->manager->persist($instance);
        }

    }
    /**
     * Create api fixtures
     */
    protected function createApis(): void
    {
        foreach (self::APIS as $config) {
            $instance = new Api();
            $instance->setIdentifier($config['identifier'])
                ->setVendor($config['vendor'])
                ->setDescription($config['description'])
                ->setVersion($config['version'])
                ->setName($config['name']);

            $this->manager->persist($instance);
        }

    }
}
