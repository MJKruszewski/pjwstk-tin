<?php

namespace App\Entity;

use App\Repository\ShipCrewmatesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShipCrewmatesRepository::class)
 */
class ShipCrewmates
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ship::class, inversedBy="shipCrewmates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ship;

    /**
     * @ORM\ManyToOne(targetEntity=Crewmate::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $crewmate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShip(): ?Ship
    {
        return $this->ship;
    }

    public function setShip(?Ship $ship): self
    {
        $this->ship = $ship;

        return $this;
    }

    public function getCrewmate(): ?Crewmate
    {
        return $this->crewmate;
    }

    public function setCrewmate(?Crewmate $crewmate): self
    {
        $this->crewmate = $crewmate;

        return $this;
    }
}
