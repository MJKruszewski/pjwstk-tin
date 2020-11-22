<?php

namespace App\Entity;

use App\Repository\ShipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShipRepository::class)
 */
class Ship
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
}
