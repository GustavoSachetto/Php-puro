<?php

namespace App\Model\DatabaseManager\Diagram\DataType;

use App\Model\DatabaseManager\Diagram\Key;
use App\Model\DatabaseManager\Diagram\Blueprint;

trait Text
{
    /**
     * Método responsável por gerar colunas do tipo varchar
     */
    public function varchar(string $columnName, int $length = 255): Key
    {
        return Blueprint::generateColumn($columnName, 'varchar', $length);
    }

    /**
     * Método responsável por gerar colunas do tipo char
     */
    public function char(string $columnName, int $length = 255): Key
    {
        return Blueprint::generateColumn($columnName, 'char', $length);
    }

    /**
     * Método responsável por gerar colunas do tipo tinytext. Total max bytes: 255
     */
    public function tinyText(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'tinytext');
    }

    /**
     * Método responsável por gerar colunas do tipo text. Total max bytes: 65,535 = 64kb
     */
    public function text(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'text');
    }

    /**
     * Método responsável por gerar colunas do tipo mediumtext. Total max bytes: 16,777,215 = 16mb
     */
    public function mediumText(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'mediumtext');
    }

    /**
     * Método responsável por gerar colunas do tipo longtext. Total max bytes: 4,294,967,295 = 4gb
     */
    public function longText(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'longtext');
    }
}
