<?php
declare(strict_types=1);
namespace DWenzel\DataCollector\Entity;

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
class Endpoint implements EntityInterface
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
    private $name = '';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description = '';

    /**
     * @ORM\ManyToOne(targetEntity="DWenzel\DataCollector\Entity\Api", inversedBy="endpoints")
     */
    private $api;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getApi(): ?Api
    {
        return $this->api;
    }

    public function setApi(?Api $api): self
    {
        $this->api = $api;

        return $this;
    }
}
