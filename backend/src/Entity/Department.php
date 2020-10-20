<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
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
     * @ORM\OneToMany(targetEntity=Crewmate::class, mappedBy="department")
     */
    private $crewmates;

    /**
     * @ORM\OneToMany(targetEntity=Station::class, mappedBy="department")
     */
    private $stations;

    public function __construct()
    {
        $this->crewmates = new ArrayCollection();
        $this->stations = new ArrayCollection();
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

    /**
     * @return Collection|Crewmate[]
     */
    public function getCrewmates(): Collection
    {
        return $this->crewmates;
    }

    public function addCrewmate(Crewmate $crewmate): self
    {
        if (!$this->crewmates->contains($crewmate)) {
            $this->crewmates[] = $crewmate;
            $crewmate->setDepartment($this);
        }

        return $this;
    }

    public function removeCrewmate(Crewmate $crewmate): self
    {
        if ($this->crewmates->contains($crewmate)) {
            $this->crewmates->removeElement($crewmate);
            // set the owning side to null (unless already changed)
            if ($crewmate->getDepartment() === $this) {
                $crewmate->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Station[]
     */
    public function getStations(): Collection
    {
        return $this->stations;
    }

    public function addStation(Station $station): self
    {
        if (!$this->stations->contains($station)) {
            $this->stations[] = $station;
            $station->setDepartment($this);
        }

        return $this;
    }

    public function removeStation(Station $station): self
    {
        if ($this->stations->contains($station)) {
            $this->stations->removeElement($station);
            // set the owning side to null (unless already changed)
            if ($station->getDepartment() === $this) {
                $station->setDepartment(null);
            }
        }

        return $this;
    }
}
