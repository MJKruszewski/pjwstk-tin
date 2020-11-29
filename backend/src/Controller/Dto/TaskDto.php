<?php


namespace App\Controller\Dto;


use App\Entity\Task;
use Symfony\Component\Validator\Constraints as Assert;

class TaskDto
{
    /**
     * @Assert\Choice(choices=Task::ALL_STATUSES)
     */
    public $status;
    /**
     * @Assert\Type(type="integer")
     */
    public $assignee;


}