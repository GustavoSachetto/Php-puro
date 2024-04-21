<?php

namespace App\Common\CommandLine;

use App\Common\CommandLine\Interaction\Database as DBInteraction;

class Command
{
    /** 
     * Comandos permitidos no terminal
    */
    private static string $short = 'd:';
    
    /** 
     * Comandos permitidos no terminal
    */
    private static array $long = ['db:'];

    /** 
     * Método responsável por processar os comandos enviados no terminal
    */
    public static function process(): void
    {
        $options = getopt(self::$short, self::$long);
        $command = array_key_first($options);

        switch ($command) {
            case 'd':
            case 'db':
                DBInteraction::verifyArgument($options[$command]);
                break;
            
            default:
                echo '[error] Comando inválido.';
                break;
        }
    }
}
