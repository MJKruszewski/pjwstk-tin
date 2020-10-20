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
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="crewmates")
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="assignee")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity=Token::class, mappedBy="crewmate")
     */
    private $tokens;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=StationWorkers::class, mappedBy="crewmate", orphanRemoval=true)
     */
    private $stationWorkers;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->tokens = new ArrayCollection();
        $this->stations = new ArrayCollection();
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    /**
     * @return Collection|Token[]
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function addToken(Token $token): self
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens[] = $token;
            $token->setCrewmate($this);
        }

        return $this;
    }

    public function removeToken(Token $token): self
    {
        if ($this->tokens->contains($token)) {
            $this->tokens->removeElement($token);
            // set the owning side to null (unless already changed)
            if ($token->getCrewmate() === $this) {
                $token->setCrewmate(null);
            }
        }

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
            $stationWorker->setCrewmate($this);
        }

        return $this;
    }

    public function removeStationWorker(StationWorkers $stationWorker): self
    {
        if ($this->stationWorkers->contains($stationWorker)) {
            $this->stationWorkers->removeElement($stationWorker);
            // set the owning side to null (unless already changed)
            if ($stationWorker->getCrewmate() === $this) {
                $stationWorker->setCrewmate(null);
            }
        }

        return $this;
    }
}
