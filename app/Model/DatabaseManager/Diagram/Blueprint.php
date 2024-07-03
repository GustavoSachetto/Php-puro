<?php

namespace App\Model\DatabaseManager\Diagram;

use App\Model\DatabaseManager\Diagram\Key;
use App\Model\DatabaseManager\Diagram\DataType\Date;
use App\Model\DatabaseManager\Diagram\DataType\Text;
use App\Model\DatabaseManager\Diagram\DataType\Custom;
use App\Model\DatabaseManager\Diagram\DataType\Number;

class Blueprint
{
    use Text;
    use Number;
    use Date;
    use Custom;

    /** 
     * Variável responsável por armazenar todas as colunas pertencentes a tabela criada
    */
    protected static array $columns;

    /** 
     * Cria um desenho técnico das colunas da nova tabela do banco de dados
    */
    public function __construct()
    {
        self::$columns = [];
    }

    /** 
     * Método responsável por gerar uma coluna para uma tabela do banco de dados
    */
    protected static function generateColumn(
        string $columnName, 
        string $dataType, 
        int|float $length = null, 
        bool $unsigned = false, 
        bool $increment = false
        ): Key
    {
        $length    == null ? $length     = '' : $length    = "(".str_replace(".", ",", $length).")";
        $unsigned  == false ? $unsigned  = '' : $unsigned  = "unsigned";
        $increment == false ? $increment = '' : $increment = "auto_increment";
        
        return self::$columns[] = new Key("$columnName $dataType $length $unsigned $increment");
    }

    /** 
     * Método responsável por pegar todas as colunas geradas no diagrama
    */
    public static function getColumns(callable $function): string
    {
        $function(new Blueprint);

        return implode(', ', array_map(fn($column) => $column->attributes, self::$columns));
    }
}