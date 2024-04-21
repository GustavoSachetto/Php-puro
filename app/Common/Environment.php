<?php

namespace App\Common;

class Environment
{
    /**
     * Método resonsável por carregar as variáveis de ambiente do projeto
     */
    public static function load(string $dir): bool
    {
        if (!file_exists($dir.'/.env')) {
            return false;
        }

        $lines = file($dir.'/.env');
        foreach ($lines as $line) {
            if (!empty(trim($line)))  {
                putenv(trim($line));
            }
        } return true;
    }
}
