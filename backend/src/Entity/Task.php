<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task implements \JsonSerializable
{
    const HIGH_PRIORITY = 'high';
    const MEDIUM_PRIORITY = 'medium';
    const LOW_PRIORITY = 'low';

    const STATUS_NEW = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DONE = 'done';

    const CODE_ENGINE_CHECK = 'engine_check';
    const CODE_ENGINE_CALIBRATIONS = 'engine_calibrations';
    const CODE_ENGINE_ISSUE = 'engine_issue';
    const CODE_MEDICAL_TREATMENT = 'medical_treatment';
    const CODE_NURSE_HELP = 'nurse_help';
    const CODE_SHIP_NAVIGATION = 'ship_navigation';
    const CODE_SHIP_DRIVING = 'ship_driving';

    const ALL_STATUSES = [
        self::STATUS_NEW,
        self::STATUS_PROCESSING,
        self::STATUS_DONE,
    ];

    const ALL_CODES = [
        self::CODE_ENGINE_CHECK,
        self::CODE_ENGINE_CALIBRATIONS,
        self::CODE_ENGINE_ISSUE,
        self::CODE_MEDICAL_TREATMENT,
        self::CODE_NURSE_HELP,
        self::CODE_SHIP_NAVIGATION,
        self::CODE_SHIP_DRIVING,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity=Crewmate::class)
     */
    private $reporter;

    /**
     * @ORM\ManyToOne(targetEntity=Crewmate::class, inversedBy="tasks")
     */
    private $assignee;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Ship::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ship;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class, inversedBy="tasks")
     */
    private $station;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getReporter(): ?Crewmate
    {
        return $this->reporter;
    }

    public function setReporter(?Crewmate $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
    }

    public function getAssignee(): ?Crewmate
    {
        return $this->assignee;
    }

    public function setAssignee(?Crewmate $assignee): self
    {
        $this->assignee = $assignee;

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

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeInterface $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

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
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'assignee' => $this->getAssignee() ? [
                'id' => $this->getAssignee()->getId(),
                'name' => $this->getAssignee()->getName(),
                'lastName' => $this->getAssignee()->getLastName(),
            ] : null,
            'priority' => $this->getPriority(),
            'status' => $this->getStatus(),
            'reporter' => [
                'id' => $this->getReporter()->getId(),
                'name' => $this->getReporter()->getName(),
                'lastName' => $this->getReporter()->getLastName(),
            ],
        ];
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
