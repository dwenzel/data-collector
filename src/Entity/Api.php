<?php

namespace DWenzel\DataCollector\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Api class
 *
 * Represents an API providing endpoints for data
 * collection.
 * An application instance may implement one or more API endpoints.
 *
 * @ORM\Entity(repositoryClass="DWenzel\DataCollector\Repository\ApiRepository")
 */
class Api implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vendor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="DWenzel\DataCollector\Entity\Endpoint", mappedBy="api", cascade={"persist", "remove"})
     */
    private $endpoints;

    /**
     * @ORM\ManyToMany(targetEntity="DWenzel\DataCollector\Entity\Instance", mappedBy="apis")
     */
    private $instances;

    public function __construct()
    {
        $this->endpoints = new ArrayCollection();
        $this->instances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Endpoint[]
     */
    public function getEndpoints(): Collection
    {
        return $this->endpoints;
    }

    public function addEndpoint(Endpoint $endpoint): self
    {
        if (!$this->endpoints->contains($endpoint)) {
            $this->endpoints[] = $endpoint;
            $endpoint->setApi($this);
        }

        return $this;
    }

    public function removeEndpoint(Endpoint $endpoint): self
    {
        if ($this->endpoints->contains($endpoint)) {
            $this->endpoints->removeElement($endpoint);
            // set the owning side to null (unless already changed)
            if ($endpoint->getApi() === $this) {
                $endpoint->setApi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Instance[]
     */
    public function getInstances(): Collection
    {
        return $this->instances;
    }

    public function addInstance(Instance $instance): self
    {
        if (!$this->instances->contains($instance)) {
            $this->instances[] = $instance;
            $instance->addApi($this);
        }

        return $this;
    }

    public function removeInstance(Instance $instance): self
    {
        if ($this->instances->contains($instance)) {
            $this->instances->removeElement($instance);
            $instance->removeApi($this);
        }

        return $this;
    }
}
