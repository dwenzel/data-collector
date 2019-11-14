<?php

namespace DWenzel\DataCollector\Tests\Unit\Entity;

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

    public function testGetIdInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getId()
        );
    }

    public function testNameCanBeSet()
    {
        $name = 'foo';
        $this->subject->setName($name);

        $this->assertSame(
            $name,
            $this->subject->getName()
        );
    }

    public function testUuidCanBeSet()
    {
        $uuid = 'bar';
        $this->subject->setUuid($uuid);

        $this->assertSame(
            $uuid,
            $this->subject->getUuid()
        );
    }

    public function testGetRoleReturnsInitialValue()
    {
        $this->assertSame(
            Instance::ROLE_UNKNOWN,
            $this->subject->getRole()
        );
    }

    public function testRoleCanBeSet()
    {
        $role = 'production';
        $this->subject->setRole($role);
        $this->assertSame(
            $role,
            $this->subject->getRole()
        );
    }
}
