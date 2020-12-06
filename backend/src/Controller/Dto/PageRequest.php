<?php


namespace App\Controller\Dto;


class PageRequest
{
    /**
     * @var int
     * @Assert\Type(type="integer")
     */
    public $itemsPerPage;
    /**
     * @var int
     * @Assert\Type(type="integer")
     */
    public $page;
}