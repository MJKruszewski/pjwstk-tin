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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="stations")
     */
    private $department;

    /**
     * @ORM\ManyToMany(targetEntity=Crewmate::class, inversedBy="stations")
     */
    private $workers;

    /**
     * @ORM\OneToMany(targetEntity=StationWorkers::class, mappedBy="station", orphanRemoval=true)
     */
    private $stationWorkers;

    public function __construct()
    {
        $this->workers = new ArrayCollection();
        $this->stationWorkers = new ArrayCollection();
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
     * @return Collection|StationWorkers[]
     */
    public function getStationWorkers(): Collection
    {
        return $this->stationWorkers;
    }

    public function addStationWorker(StationWorkers $stationWorker): self
    {
        if (!$this->stationWorkers->contains($stationWorker)) {
            $this->stationWorkers[] = $stationWorker;
            $stationWorker->setStation($this);
        }

        return $this;
    }

    public function removeStationWorker(StationWorkers $stationWorker): self
    {
        if ($this->stationWorkers->contains($stationWorker)) {
            $this->stationWorkers->removeElement($stationWorker);
            // set the owning side to null (unless already changed)
            if ($stationWorker->getStation() === $this) {
                $stationWorker->setStation(null);
            }
        }

        return $this;
    }
}
