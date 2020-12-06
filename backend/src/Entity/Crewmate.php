<?php

namespace App\Entity;

use App\Repository\CrewmateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrewmateRepository::class)
 */
class Crewmate implements \JsonSerializable
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
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="assignee", cascade={"detach"})
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity=ShipCrewmates::class, mappedBy="crewmate", orphanRemoval=true)
     */
    private $shipCrewmates;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="crewmates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mainDepartment;

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
        $this->password = hash('sha512', $password);

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMainDepartment(): ?Department
    {
        return $this->mainDepartment;
    }

    public function setMainDepartment(?Department $mainDepartment): self
    {
        $this->mainDepartment = $mainDepartment;

        return $this;
    }


    public function isCaptain(): bool
    {
        /** @var CrewmateStations $crewmateStation */
        foreach ($this->crewmateStations as $crewmateStation) {
            if ($crewmateStation->getStation()->getCode() === Station::CAPTAIN_CODE && $crewmateStation->isNotExpired()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Ship
     */
    public function getShip(): ?Ship
    {
        return $this->shipCrewmates->first()->getShip() ?? null;
    }

    /**
     * @return mixed
     */
    public function getShipCrewmates()
    {
        return $this->shipCrewmates;
    }

    /**
     * @param mixed $shipCrewmate
     */
    public function setShipCrewmates($shipCrewmate): void
    {
        if (!$this->shipCrewmates->contains($shipCrewmate)) {
            $this->shipCrewmates[] = $shipCrewmate;
            $shipCrewmate->setCrewmate($this);
        }
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
            'id' => $this->id,
            'name' => $this->name,
            'lastName' => $this->lastName,
            'login' => $this->login,
            'stats' => $this->crewmateStats,
            'stations' => $this->crewmateStations->getValues() ?? [],
            'tasks' => array_filter($this->tasks->getValues() ?? [], function (Task $task) {
                    return $task->getStatus() !== Task::STATUS_DONE;
                }) ?? [],
            'roles' => array_map(function (CrewmateStations $crewmateStations) {
                return $crewmateStations->getStation()->getCode();
            }, array_filter($this->getCrewmateStations()->toArray(), function (CrewmateStations $crewmateStations) {
                if ($crewmateStations->getDateTo() === null) {
                    return true;
                }

                if ($crewmateStations->getDateTo() > new \DateTime()) {
                    return true;
                }

                return false;
            })),
            'ship' => $this->shipCrewmates->first() ?? [],
            'createdAt' => $this->createdAt,
            'mainDepartment' => $this->mainDepartment,
        ];
    }



}
