<?php

namespace DWenzel\DataCollector\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Endpoint;
use DWenzel\DataCollector\Entity\Instance;
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

    public function testGetIdInitiallyReturnsNull(): void
    {
        $this->assertNull(
            $this->subject->getId()
        );
    }

    public function testVendorCanBeSet(): void
    {
        $vendor = 'foo';
        $this->subject->setVendor($vendor);
        $this->assertSame(
            $vendor,
            $this->subject->getVendor()
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

    public function testVersionCanBeSet(): void
    {
        $version = 'foo';
        $this->subject->setVersion($version);
        $this->assertSame(
            $version,
            $this->subject->getVersion()
        );
    }

    public function testIdentifierCanBeSet(): void
    {
        $identifier = 'foo';
        $this->subject->setIdentifier($identifier);
        $this->assertSame(
            $identifier,
            $this->subject->getIdentifier()
        );
    }

    public function testDescriptionCanBeSet(): void
    {
        $description = 'foo';
        $this->subject->setDescription($description);
        $this->assertSame(
            $description,
            $this->subject->getDescription()
        );
    }

    public function testGetEndpointsInitiallyReturnsEmptyCollection(): void
    {
        $this->assertEmpty(
            $this->subject->getEndpoints()
        );
    }

    public function testEndpointCanBeAdded(): void
    {
        $endpoint = $this->createMock(Endpoint::class);
        $this->subject->addEndpoint($endpoint);

        $this->assertContains(
            $endpoint,
            $this->subject->getEndpoints()
        );
    }

    public function testAddingEndpointSetsApi(): void
    {
        $endpoint = $this->createMock(Endpoint::class);

        $endpoint->expects($this->once())
            ->method('setApi')
            ->with($this->subject);

        $this->subject->addEndpoint($endpoint);
    }

    public function testEndpointCanBeRemoved(): void
    {
        $endpoint = $this->createMock(Endpoint::class);
        $this->subject->addEndpoint($endpoint);

        $this->subject->removeEndpoint($endpoint);
        $this->assertNotContains(
            $endpoint,
            $this->subject->getEndpoints()
        );
    }

    public function testRemovingEndpointUnsetsApi(): void
    {
        $endpoint = new Endpoint();
        $this->subject->addEndpoint($endpoint);

        $this->assertSame(
            $this->subject,
            $endpoint->getApi()
        );

        $this->subject->removeEndpoint($endpoint);
        $this->assertEmpty($endpoint->getApi());
    }

    public function testGetInstancesInitiallyReturnsEmptyCollection(): void
    {
        $this->assertInstanceOf(
            ArrayCollection::class,
            $this->subject->getInstances()
        );

        $this->assertEmpty($this->subject->getInstances());
    }

    public function testInstanceCanBeAdded(): void
    {
        $instance = $this->createMock(Instance::class);
        $this->subject->addInstance($instance);
        $this->assertContains(
            $instance,
            $this->subject->getInstances()
        );
    }

    public function testInstanceCanBeRemoved(): void
    {
        $instance = $this->createMock(Instance::class);
        $this->subject->addInstance($instance)
            ->removeInstance($instance);
        $this->assertNotContains(
            $instance,
            $this->subject->getInstances()
        );
    }
}
