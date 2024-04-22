<?php

namespace App\Common\CommandLine\Interaction;

use FilesystemIterator;
use App\Common\CommandLine\Required\Interaction;

class Database
{
    /** 
     * Nome dos arquivos incluidos
    */
    private static array $fileName;

    /** 
     * Método responsável por incluir os arquivos
    */
    private static function includeFiles(string $dir): void
    {
        $iterator = new FilesystemIterator("./database/$dir");

        foreach ($iterator as $file) {
            include $file->getPathname();
            self::$fileName[] = $file->getFilename();
        }
    }

    /** 
     * Método responsável por setar a interação no banco de dados
    */
    public static function setInteraction(string $type, string $dir): void
    {
        self::includeFiles($dir);
        $interactions = Interaction::$interactions;

        if ($type == 'down') krsort($interactions);

        foreach ($interactions as $key => $interact) {
            $interact->$type();
            echo "[success] $type: ".self::$fileName[$key]."\n";
        }

        Interaction::clear();
    }

    /** 
     * Método responsável por verificar os argumentos recebidos
    */
    public static function verifyArgument(string $argument): void
    {
        switch ($argument) {
            case 'set':
                self::setInteraction('up', 'schema');
                break;
            case 'drop':
                self::setInteraction('down', 'schema');
                break;
            case 'reset':
                self::setInteraction('down', 'schema');
                self::setInteraction('up', 'schema');
                break;
            case 'load':
                self::setInteraction('up', 'information');
                break;
            case 'fresh':
                self::setInteraction('down', 'information');
                break;
            
            default:
                echo '[error] Argumento inválido.';
                break;
        }
    }
}
