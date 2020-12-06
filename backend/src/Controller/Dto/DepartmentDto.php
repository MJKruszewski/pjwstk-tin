<?php


namespace App\Controller\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class DepartmentDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="15")
     * @Assert\Type(type="string")
     */
    public $code;
}