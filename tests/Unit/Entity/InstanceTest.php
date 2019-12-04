<?php

namespace DWenzel\DataCollector\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Instance;
use PHPUnit\Framework\TestCase;

class InstanceTest extends TestCase
{
    /**
     * @var Instance
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new Instance();
    }

    public function testGetIdInitiallyReturnsNull(): void
    {
        $this->assertNull(
            $this->subject->getId()
        );
    }

    public function testNameCanBeSet(): void
    {
        $name = 'foo';
        $this->subject->setName($name);

        $this->assertSame(
            $name,
            $this->subject->getName()
        );
    }

    public function testUuidCanBeSet(): void
    {
        $uuid = 'bar';
        $this->subject->setUuid($uuid);

        $this->assertSame(
            $uuid,
            $this->subject->getUuid()
        );
    }

    public function testGetRoleReturnsInitialValue(): void
    {
        $this->assertSame(
            Instance::ROLE_UNKNOWN,
            $this->subject->getRole()
        );
    }

    public function testRoleCanBeSet(): void
    {
        $role = 'production';
        $this->subject->setRole($role);
        $this->assertSame(
            $role,
            $this->subject->getRole()
        );
    }

    public function testGetApiInitiallyReturnsEmptyCollection(): void
    {
        $this->assertInstanceOf(
            ArrayCollection::class,
            $this->subject->getApis()
        );

        $this->assertEmpty(
            $this->subject->getApis()
        );
    }

    public function testApiCanBeAdded(): void
    {
        $api = $this->createMock(Api::class);
        $this->subject->addApi($api);

        $this->assertContains(
            $api,
            $this->subject->getApis()
        );
    }

    public function testApiCanBeRemoved(): void
    {
        $api = $this->createMock(Api::class);
        $this->subject->addApi($api);

        $this->assertContains(
            $api,
            $this->subject->getApis()
        );

        $this->subject->removeApi($api);
        $this->assertNotContains(
            $api,
            $this->subject->getApis()
        );
    }
}
