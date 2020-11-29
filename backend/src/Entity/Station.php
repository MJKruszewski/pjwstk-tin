<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 */
class Station implements \JsonSerializable
{

    const CAPTAIN_CODE = 'captain';

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

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="station")
     */
    private $tasks;

    public function __construct()
    {
        $this->crewmateStations = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'department' => [
                'id' => $this->getDepartment()->getId(),
                'code' => $this->getDepartment()->getCode(),
            ]
        ];
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setStation($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getStation() === $this) {
                $task->setStation(null);
            }
        }

        return $this;
    }
}
