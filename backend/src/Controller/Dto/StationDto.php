<?php


namespace App\Controller\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class StationDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="15")
     * @Assert\Type(type="string")
     */
    public $code;
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    public $departmentId;

}