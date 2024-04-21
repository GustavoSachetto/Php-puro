<?php

namespace App\Model\DatabaseManager\Diagram\DataType;

use App\Model\DatabaseManager\Diagram\Key;
use App\Model\DatabaseManager\Diagram\Blueprint;

trait Custom
{
    /** 
     * Método responsável por gerar uma tabela de id no banco de dados
    */
    public function id(): Key
    {
        return Blueprint::generateColumn('id', 'bigint', null, true, true)->primary();
    }

    /** 
     * Método reponsável por gerar uma chave foreign key
    */
    public function foreign(
        string $column, 
        string $foreignTable, 
        string $foreignColumn
        ): Key
    {
        return Blueprint::$columns[] = new Key(" foreign key ($column) references $foreignTable($foreignColumn)");
    }

    /** 
     * Método reponsável por gerar uma chave constraint
    */
    public function constraint(string $name, string $type, array $array): Key
    {
        if ($type == 'foreign key') {
            return Blueprint::$columns[] = new Key(" 
                constraint $name $type (".$array[0].") 
                references ".$array[1]."(".$array[2].")
            ");
        } else if ($type == 'unique') {
            return Blueprint::$columns[] = new Key(" constraint $name $type (".implode(',', $array).")");
        }
    }
}
