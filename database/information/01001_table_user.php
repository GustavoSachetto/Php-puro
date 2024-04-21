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
        (new Database('user'))->insert([
            'name' => 'Usuário Admin',
            'email' => 'admin@email.com',
            'admin_access' => true,
            'password_hash' => '$2y$10$mUsr/jxR6XXs8lgXeaukPu/zkkOTIsX2dUus43h9sDBCuDu/upY/e'
        ]);
    }

    /** 
     * Método responsável por descer a infomação no banco de dados.
    */
    public function down(): void
    {
        (new Database('user'))->securityDelete('id = 1');
    }
};
