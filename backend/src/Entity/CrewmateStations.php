<?php

namespace App\Entity;

use App\Repository\CrewmateStationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrewmateStationsRepository::class)
 */
class CrewmateStations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Crewmate::class, inversedBy="crewmateStations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $crewmate;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="crewmateStations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $station;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFrom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTimeInterface $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->dateTo;
    }

    public function setDateTo(?\DateTimeInterface $dateTo): self
    {
        $this->dateTo = $dateTo;

        return $this;
    }
}
