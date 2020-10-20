<?php

namespace App\Entity;

use App\Repository\StationWorkersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StationWorkersRepository::class)
 */
class StationWorkers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFrom;

    /**
     * @ORM\ManyToOne(targetEntity=Crewmate::class, inversedBy="stationWorkers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $crewmate;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="stationWorkers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $station;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(?\DateTimeInterface $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

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

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }
}
