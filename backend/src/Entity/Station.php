<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 */
class Station
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="stations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=CrewmateStations::class, mappedBy="station", orphanRemoval=true)
     */
    private $crewmateStations;

    public function __construct()
    {
        $this->crewmateStations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|CrewmateStations[]
     */
    public function getCrewmateStations(): Collection
    {
        return $this->crewmateStations;
    }

    public function addCrewmateStation(CrewmateStations $crewmateStation): self
    {
        if (!$this->crewmateStations->contains($crewmateStation)) {
            $this->crewmateStations[] = $crewmateStation;
            $crewmateStation->setStation($this);
        }

        return $this;
    }

    public function removeCrewmateStation(CrewmateStations $crewmateStation): self
    {
        if ($this->crewmateStations->contains($crewmateStation)) {
            $this->crewmateStations->removeElement($crewmateStation);
            // set the owning side to null (unless already changed)
            if ($crewmateStation->getStation() === $this) {
                $crewmateStation->setStation(null);
            }
        }

        return $this;
    }
}
