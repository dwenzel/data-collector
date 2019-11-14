<?php

namespace DWenzel\DataCollector\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use DWenzel\DataCollector\Entity\Instance;

class DataCollectorFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    const INSTANCES = [
        [
            'identifier' => '111301a-59d9-4146-9810-a1f376327f44',
            'name' => 'foo',
            'role'  => 'production/staging'
        ],
        [
            'identifier' => '2225301a-59d9-4146-9810-a1f376327fae',
            'name' => 'foo',
            'role'  => 'testing'
        ],
        [
            'identifier' => '3335301a-59d9-4146-9810-a1f376327f44',
            'name' => 'bar',
            'role'  => ''
        ],
        [
            'identifier' => '4445301a-59d9-4146-9810-a1f376327f44',
            'name' => 'bar',
            'role'  => ''
        ],


    ];

    /**
     * Load data fixtures
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->createInstances();
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
}
