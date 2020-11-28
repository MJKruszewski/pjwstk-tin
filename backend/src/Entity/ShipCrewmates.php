<?php

namespace App\Entity;

use App\Repository\ShipCrewmatesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShipCrewmatesRepository::class)
 */
class ShipCrewmates implements \JsonSerializable
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

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->getShip()->jsonSerialize();
    }
}
