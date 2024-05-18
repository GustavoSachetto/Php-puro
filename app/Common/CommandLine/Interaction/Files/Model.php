<?php

namespace App\Model\Entity;

use PDOStatement;
use App\Model\DatabaseManager\Database;

class FileModel
{
    public int $id;
    
    public string $columnName;

    public bool $deleted = false;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     */
    public function create(): bool
    {
        $this->id = (new Database('tableName'))->insert([
            'columnName'  => $this->columnName,
            'deleted'     => $this->deleted
        ]);

        return true;
    }

    /**
     * Método responsável por atualizar os dados do banco com a instância atual
     */
    public function update(): bool
    {
        return (new Database('tableName'))->update('id = '.$this->id, [
            'columnName'  => $this->columnName,
            'deleted'     => $this->deleted
        ]);
    }

    /**
     * Método responsável por excluir um dado no banco com a instância atual
     */
    public function delete(): bool
    {
        return (new Database('tableName'))->securityDelete('id = '.$this->id);
    }

    /**
     * Método que retorna os dados cadastrados no banco
     */
    public static function getTablesName(
        string $where = null, 
        string $order = null, 
        string $limit = null, 
        string $fields = '*'
        ): PDOStatement
    {
        return (new Database('tableName'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método reponsável por retornar um dado com base no seu ID
     */
    public static function getTableNameById(int $id): FileModel|string
    {
        return self::getTablesName("id = {$id}")->fetchObject(self::class);
    }
}
