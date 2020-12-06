<?php


namespace App\Controller\Dto;

use App\Entity\Task;
use Symfony\Component\Validator\Constraints as Assert;

class AddTaskDto
{
    /**
     * @Assert\Choice(choices=Task::ALL_CODES)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $code;
    /**
     * @Assert\Choice(choices=Task::PRIORITIES)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $priority;
    /**
     * @var int
     * @Assert\Type(type="integer")
     */
    public $shipId;
    /**
     * @var int
     * @Assert\Type(type="integer")
     */
    public $stationId;
}