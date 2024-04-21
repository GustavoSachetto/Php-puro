<?php

namespace App\Common\CommandLine\Required;

class Interaction
{
    public static array $interactions;

    public function __construct()
    {
        self::$interactions[] = $this;
    }

    public static function clear()
    {
        self::$interactions = [];
    }
}
