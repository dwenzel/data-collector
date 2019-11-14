<?php

namespace DWenzel\DataCollector\Tests\Unit\Entity;

use DWenzel\DataCollector\Entity\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    /**
     * @var Api
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new Api();
    }

    public function testGetIdInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getId()
        );
    }
    
    public function testVendorCanBeSet()
    {
        $vendor = 'foo';
        $this->subject->setVendor($vendor);
        $this->assertSame(
            $vendor,
            $this->subject->getVendor()
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

    public function testVersionCanBeSet()
    {
        $version = 'foo';
        $this->subject->setVersion($version);
        $this->assertSame(
            $version,
            $this->subject->getVersion()
        );
    }

    public function testIdentifierCanBeSet()
    {
        $identifier = 'foo';
        $this->subject->setIdentifier($identifier);
        $this->assertSame(
            $identifier,
            $this->subject->getIdentifier()
        );
    }

    public function testDescriptionCanBeSet()
    {
        $description = 'foo';
        $this->subject->setDescription($description);
        $this->assertSame(
            $description,
            $this->subject->getDescription()
        );
    }

}
