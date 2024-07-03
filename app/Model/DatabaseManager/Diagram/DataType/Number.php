<?php

namespace App\Model\DatabaseManager\Diagram\DataType;

use App\Model\DatabaseManager\Diagram\Key;
use App\Model\DatabaseManager\Diagram\Blueprint;

trait Number
{
    /**
     * Método responsável por gerar colunas do tipo tinyint. Total de armazenamento: (min: -128, max: 127)
     */
    public function tinyInt(
        string $columnName, 
        bool $unsigned = false, 
        bool $autoIncrement = false
        ): Key
    {
        return Blueprint::generateColumn($columnName, 'tinyint', null, $unsigned, $autoIncrement);
    }

    /**
     * Método responsável por gerar colunas do tipo smallint. Total de armazenamento: (min: -32768, max: 32767)
     */
    public function smallInt(
        string $columnName, 
        bool $unsigned = false, 
        bool $autoIncrement = false
        ): Key
    {
        return Blueprint::generateColumn($columnName, 'smallint', null, $unsigned, $autoIncrement);
    }

    /**
     * Método responsável por gerar colunas do tipo mediumint. Total de armazenamento: (min: -8388608, max: 8388607)
     */
    public function mediumInt(
        string $columnName, 
        bool $unsigned = false, 
        bool $autoIncrement = false
        ): Key
    {
        return Blueprint::generateColumn($columnName, 'mediumint', null, $unsigned, $autoIncrement);
    }
    
    /**
     * Método responsável por gerar colunas do tipo int. Total de armazenamento: (min: -2147483648, max: 2147483647)
     */
    public function int(
        string $columnName, 
        bool $unsigned = false, 
        bool $autoIncrement = false
        ): Key
    {
        return Blueprint::generateColumn($columnName, 'int', null, $unsigned, $autoIncrement);
    }

    /**
     * Método responsável por gerar colunas do tipo bigint. Total de armazenamento: (min: -2^63, max: 2^63-1)
     */
    public function bigInt(
        string $columnName, 
        bool $unsigned = false, 
        bool $autoIncrement = false
        ): Key
    {
        return Blueprint::generateColumn($columnName, 'bigint', null, $unsigned, $autoIncrement);
    }

    /**
     * Método responsável por gerar colunas do tipo boleano (tinyint)
     */
    public function boolean(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'boolean');
    }

    /**
     * Método responsável por gerar colunas do tipo float
     */
    public function double(string $columnName): Key
    {
        return Blueprint::generateColumn($columnName, 'double');
    }

    /**
     * Método responsável por gerar colunas do tipo decimal
     */
    public function decimal(string $columnName, float $format): Key
    {
        return Blueprint::generateColumn($columnName, 'decimal', $format);
    }

    /**
     * Método responsável por gerar colunas do tipo float
     */
    public function float(string $columnName, float $format): Key
    {
        return Blueprint::generateColumn($columnName, 'float', $format);
    }
}
