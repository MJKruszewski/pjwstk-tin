<?php

namespace App\Entity;

use App\Repository\ShipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShipRepository::class)
 */
class Ship implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ShipCrewmates::class, mappedBy="ship")
     */
    private $shipCrewmates;

    /**
     * @ORM\Column(type="integer")
     */
    private $hull;

    /**
     * @ORM\Column(type="integer")
     */
    private $engines;

    public function __construct()
    {
        $this->shipCrewmates = new ArrayCollection();
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

    /**
     * @return Collection|ShipCrewmates[]
     */
    public function getShipCrewmates(): Collection
    {
        return $this->shipCrewmates;
    }

    public function addShipCrewmate(ShipCrewmates $shipCrewmate): self
    {
        if (!$this->shipCrewmates->contains($shipCrewmate)) {
            $this->shipCrewmates[] = $shipCrewmate;
            $shipCrewmate->setShip($this);
        }

        return $this;
    }

    public function removeShipCrewmate(ShipCrewmates $shipCrewmate): self
    {
        if ($this->shipCrewmates->contains($shipCrewmate)) {
            $this->shipCrewmates->removeElement($shipCrewmate);
            // set the owning side to null (unless already changed)
            if ($shipCrewmate->getShip() === $this) {
                $shipCrewmate->setShip(null);
            }
        }

        return $this;
    }

    public function getHull(): ?int
    {
        return $this->hull;
    }

    public function setHull(int $hull): self
    {
        $this->hull = $hull;

        return $this;
    }

    public function getEngines(): ?int
    {
        return $this->engines;
    }

    public function setEngines(int $engines): self
    {
        $this->engines = $engines;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hull' => $this->hull,
            'engines' => $this->engines,
        ];
    }
}
