<?php


namespace App\Singleton;


class Encrypt
{

    public static function hash(string $value): string
    {
        return hash('sha512', $value);
    }

}