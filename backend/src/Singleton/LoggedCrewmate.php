<?php


namespace App\Singleton;


use App\Entity\Crewmate;

class LoggedCrewmate
{
    /** @var Crewmate|null */
    public static $crewmate = null;

    /**
     * @return null|Crewmate
     */
    public static function getCrewmate(): ?Crewmate
    {
        return self::$crewmate;
    }

    /**
     * @param Crewmate $crewmate
     */
    public static function setCrewmate(Crewmate $crewmate): void
    {
        self::$crewmate = $crewmate;
    }


}