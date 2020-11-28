<?php


namespace App\Controller\Dto;


use Symfony\Component\Validator\Constraints as Assert;

class LoginDto
{
    /**
     * @Assert\NotBlank(allowNull=false)
     *
     * @var string
     */
    public $login;
    /**
     * @Assert\NotBlank(allowNull=false)
     *
     * @var string
     */
    public $password;

}