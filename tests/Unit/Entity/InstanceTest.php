<?php

namespace DWenzel\DataCollector\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Endpoint;
use DWenzel\DataCollector\Entity\Instance;
use GuzzleHttp\Psr7\Uri;
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

    public function testGetBaseUrlInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getBaseUrl()
        );
    }

    public function testBaseUrlCanBeSet()
    {
        $url = 'bar';
        $this->subject->setBaseUrl($url);

        $this->assertSame(
            $url,
            $this->subject->getBaseUrl()
        );
    }

    public function testGetProtocolReturnsInitialValue()
    {
        $initialValue = 'https';
        $this->assertSame(
            $initialValue,
            $this->subject->getProtocol()
        );
    }

    public function testProtocolCanBeSet()
    {
        $protocol = 'foo';
        $this->subject->setProtocol($protocol);

        $this->assertSame(
            $protocol,
            $this->subject->getProtocol()
        );
    }

    public function testGetUrlsReturnsCollection()
    {
        $this->assertInstanceOf(
            Collection::class,
            $this->subject->getUrls()
        );
    }

    public function testGetUrlsBuildUrlFromApiAndEndpoint()
    {
        $protocol = 'https';
        $host = 'foo.bar';
        $path = 'you/dot.there';

        $endpoint = $this->createMock(Endpoint::class);
        $endpoint->method('getName')
            ->willReturn($path);
        $endpoints = new ArrayCollection([$endpoint]);
        $api = $this->createMock(Api::class);
        $api->method('getEndpoints')
            ->willReturn($endpoints);

        $this->subject
            ->setBaseUrl($host)
            ->setProtocol($protocol)
            ->addApi($api);

        $uri = Uri::fromParts(
            [
                'scheme' => $protocol,
                'host' => $host,
                'path' => $path
            ]
        );
        $expectedUrl = $uri->__toString();

        $this->assertSame(
            $expectedUrl,
            $this->subject->getUrls()->first()
        );

    }
}
