<?php

namespace App\Model\DatabaseManager\Diagram\DataType;

use App\Model\DatabaseManager\Diagram\Key;
use App\Model\DatabaseManager\Diagram\Blueprint;

trait Date
{
    /**
     * Método responsável por gerar colunas do tipo date. Formato padrão: yyyy-dd-mm
     */
    public function date(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'date');
    }

    /**
     * Método responsável por gerar colunas do tipo time. Formato padrão: hh:mm:ss
     */
    public function time(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'time');
    }

    /**
     * Método responsável por gerar colunas do tipo dateTime. Formato padrão: yyyy-dd-mm hh:mm:ss
     */
    public function datetime(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'datetime');
    }

    /**
     * Método responsável por gerar colunas do tipo timestamp. Formato padrão: yyyy-dd-mm hh:mm:ss
     */
    public function timestamp(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'timestamp');
    }

    /**
     * Método responsável por gerar colunas do tipo year. Formato padrão: yyyy
     */
    public function year(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'year');
    }
}
