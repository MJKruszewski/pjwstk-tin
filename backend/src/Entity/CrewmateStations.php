<?php

namespace App\Entity;

use App\Repository\CrewmateStationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrewmateStationsRepository::class)
 */
class CrewmateStations implements \JsonSerializable
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
     * @var Station
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="crewmateStations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $station;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateFrom;

    /**
     * @var \DateTime
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
            'id' => $this->station->getId(),
            'code' => $this->station->getCode(),
            'from' => $this->dateFrom ? $this->dateFrom->format(DATE_ISO8601) : null,
            'to' => $this->dateTo ? $this->dateTo->format(DATE_ISO8601) : null,
            'department' => [
                'id' => $this->station->getDepartment()->getId(),
                'code' => $this->station->getDepartment()->getCode(),
            ]
        ];
    }
}
