<?php


namespace App\Controller\Dto;


use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\Validator\Constraints as Assert;

class AddCrewmateStation
{
    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    public $stationId;
    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    public $crewmateId;
    /**
     * @Assert\Date()
     */
    public $dateFrom;
}