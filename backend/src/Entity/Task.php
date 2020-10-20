<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Crewmate::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $reporter;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity=TaskStatus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Crewmate::class, inversedBy="tasks")
     */
    private $assignee;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus(): ?TaskStatus
    {
        return $this->status;
    }

    public function setStatus(?TaskStatus $status): self
    {
        $this->status = $status;

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
}
