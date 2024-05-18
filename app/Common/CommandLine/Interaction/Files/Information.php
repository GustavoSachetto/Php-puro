<?php

use App\Model\DatabaseManager\Database;
use App\Common\CommandLine\Required\Interaction;

return new class extends Interaction
{
    /** 
     * Método responsável por subir a infomação no banco de dados.
    */
    public function up(): void
    {
        (new Database('tableName'))->insert([
            'columnName' => 'value',
        ]);
    }

    /** 
     * Método responsável por descer a infomação no banco de dados.
    */
    public function down(): void
    {
        (new Database('tableName'))->securityDelete('id = 1');
    }
};
