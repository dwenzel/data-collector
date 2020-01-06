<?php

namespace DWenzel\DataCollector\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PostLoad;
use GuzzleHttp\Psr7\Uri;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @ORM\Entity(repositoryClass="DWenzel\DataCollector\Repository\InstanceRepository")
 * @HasLifecycleCallbacks
 */
class Instance implements EntityInterface
{
    public const ROLE_UNKNOWN = 'unknown';
    public const DEFAULT_PROTOCOL = 'https';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="guid")
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role = self::ROLE_UNKNOWN;

    /**
     * @ORM\ManyToMany(targetEntity="DWenzel\DataCollector\Entity\Api", inversedBy="instances")
     */
    private $apis;

    /**
     * @var ArrayCollection
     */
    private $urls;


    /**
     * @var string
     *
     * @Enum("DWenzel\DataCollector\Enum\Protocol")
     */
    private $protocol = self::DEFAULT_PROTOCOL;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $baseUrl;

    public function __construct()
    {
        $this->apis = new ArrayCollection();
        $this->urls = new ArrayCollection();
    }

    /** @PostLoad */
    public function initObject(){
        if (null == $this->urls) {
            $this->urls = new ArrayCollection();
        }
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Api[]
     */
    public function getApis(): Collection
    {
        return $this->apis;
    }

    public function addApi(Api $api): self
    {
        if (!$this->apis->contains($api)) {
            $this->apis[] = $api;
        }

        return $this;
    }

    public function removeApi(Api $api): self
    {
        if ($this->apis->contains($api)) {
            $this->apis->removeElement($api);
        }

        return $this;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * Get all URLs to collect data from
     *
     * Returns a Collection of URLs build from all endpoints
     * of all APIs of this Instance
     *
     * @return ArrayCollection|string[]
     */
    public function getUrls(): ArrayCollection
    {
        if ($this->urls->isEmpty()) {

            foreach ($this->getApis() as $api) {
                foreach ($api->getEndpoints() as $endpoint) {
                    $parts = [
                        'scheme' => $this->protocol,
                        'host' => $this->baseUrl,
                        'path' => $endpoint->getName()
                    ];
                    $uri = Uri::fromParts($parts);
                    $this->urls->add($uri->__toString());
                }
            }

        }

        return $this->urls;
    }
}
