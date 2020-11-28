<?php

namespace App\Entity;

use App\Repository\CrewmateStatsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrewmateStatsRepository::class)
 */
class CrewmateStats implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $intelligence;

    /**
     * @ORM\Column(type="smallint")
     */
    private $strength;

    /**
     * @ORM\Column(type="smallint")
     */
    private $dexterity;

    /**
     * @ORM\Column(type="smallint")
     */
    private $experience;

    /**
     * @ORM\Column(type="smallint")
     */
    private $physicalCondition;

    /**
     * @ORM\OneToOne(targetEntity=Crewmate::class, inversedBy="crewmateStats", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $crewmate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getDexterity(): ?int
    {
        return $this->dexterity;
    }

    public function setDexterity(int $dexterity): self
    {
        $this->dexterity = $dexterity;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getPhysicalCondition(): ?int
    {
        return $this->physicalCondition;
    }

    public function setPhysicalCondition(int $physicalCondition): self
    {
        $this->physicalCondition = $physicalCondition;

        return $this;
    }

    public function getCrewmate(): ?Crewmate
    {
        return $this->crewmate;
    }

    public function setCrewmate(Crewmate $crewmate): self
    {
        $this->crewmate = $crewmate;

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
            'dexterity' => $this->dexterity,
            'strength' => $this->strength,
            'physicalCondition' => $this->physicalCondition,
            'intelligence' => $this->intelligence,
            'experience' => $this->experience,
        ];
    }
}
