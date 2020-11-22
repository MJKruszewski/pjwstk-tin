<?php

namespace App\Entity;

use App\Repository\CrewmateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrewmateRepository::class)
 */
class Crewmate
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
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=600)
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=CrewmateStats::class, mappedBy="crewmate", cascade={"persist", "remove"})
     */
    private $crewmateStats;

    /**
     * @ORM\OneToMany(targetEntity=CrewmateStations::class, mappedBy="crewmate", orphanRemoval=true)
     */
    private $crewmateStations;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="assignee")
     */
    private $tasks;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $role;

    public function __construct()
    {
        $this->crewmateStations = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCrewmateStats(): ?CrewmateStats
    {
        return $this->crewmateStats;
    }

    public function setCrewmateStats(CrewmateStats $crewmateStats): self
    {
        $this->crewmateStats = $crewmateStats;

        // set the owning side of the relation if necessary
        if ($crewmateStats->getCrewmate() !== $this) {
            $crewmateStats->setCrewmate($this);
        }

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
            $crewmateStation->setCrewmate($this);
        }

        return $this;
    }

    public function removeCrewmateStation(CrewmateStations $crewmateStation): self
    {
        if ($this->crewmateStations->contains($crewmateStation)) {
            $this->crewmateStations->removeElement($crewmateStation);
            // set the owning side to null (unless already changed)
            if ($crewmateStation->getCrewmate() === $this) {
                $crewmateStation->setCrewmate(null);
            }
        }

        return $this;
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
            $task->setAssignee($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getAssignee() === $this) {
                $task->setAssignee(null);
            }
        }

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
