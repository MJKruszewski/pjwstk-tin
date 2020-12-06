<?php


namespace App\Controller\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCrewmateDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="3", max="255")
     * @Assert\Type(type="string")
     */
    public $name;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="3", max="255")
     * @Assert\Type(type="string")
     */
    public $lastName;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    public $departmentId;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    public $shipId;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="10")
     * @Assert\Type(type="integer")
     */
    public $intelligence;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="10")
     * @Assert\Type(type="integer")
     */
    public $strength;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="10")
     * @Assert\Type(type="integer")
     */
    public $dexterity;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="10")
     * @Assert\Type(type="integer")
     */
    public $experience;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="10")
     * @Assert\Type(type="integer")
     */
    public $physicalCondition;
}