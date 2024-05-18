<?php

namespace App\Common\CommandLine\Interaction;

class Builder
{
    private static string $rootDir = __DIR__.'/Files/';

    private static function cloneFile(string $from, string $to): string
    {
        if (!file_exists($from)) return '[error] Arquivo a ser clonado não existente';

        if (file_exists($to)) return '[error] Arquivo já existente. Não pode ser duplicado.';

        copy($from, $to);
        return "[success] build $to \n";
    }

    private static function setNewFile(string $fileName, string $newDir, string|null $rename): void
    {
        $file = $fileName.'.php';
        
        $from = self::$rootDir.$file;
        $to = isset($rename) ? $newDir.$rename.'.php' : $newDir.$file;

        echo self::cloneFile($from, $to);
    }

    /** 
     * Método responsável por verificar os argumentos recebidos
    */
    public static function verifyArgument(string $argument): void
    {
        $argumentEx = explode(':', $argument);
        
        $argumentFilter = $argumentEx[0];
        $rename = $argumentEx[1] ?? null;

        switch ($argumentFilter) {
            case 'model':
                self::setNewFile('Model', 'app/Model/Entity/', $rename);
                break;
            case 'controller':
                self::setNewFile('Controller', 'app/Controller/', $rename);
                break;
            case 'table':
                self::setNewFile('Table', 'database/schema/', $rename);
                self::setNewFile('Information', 'database/information/', $rename);
                break;
            
            default:
                echo '[error] Argumento inválido.';
                break;
        }
    }
}
