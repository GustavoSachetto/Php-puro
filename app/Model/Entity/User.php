<?php

namespace App\Model\Entity;

use PDOStatement;
use App\Model\DatabaseManager\Database;

class User
{
    public int $id;
    
    public string $name;
    public string $email;
    public string $password_hash;
    public bool $admin_access = false;

    public bool $deleted = false;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     */
    public function create(): bool
    {
        $this->id = (new Database('user'))->insert([
            'name'          => $this->name,
            'email'         => $this->email,
            'admin_access'  => $this->admin_access,
            'password_hash' => $this->password_hash,
            'deleted'       => $this->deleted
        ]);

        return true;
    }

    /**
     * Método responsável por atualizar os dados do banco com a instância atual
     */
    public function update(): bool
    {
        return (new Database('user'))->update('id = '.$this->id, [
            'name'          => $this->name,
            'email'         => $this->email,
            'admin_access'  => $this->admin_access,
            'password_hash' => $this->password_hash,
            'deleted'       => $this->deleted
        ]);
    }

    /**
     * Método responsável por excluir um dado no banco com a instância atual
     */
    public function delete(): bool
    {
        return (new Database('user'))->securityDelete('id = '.$this->id);
    }

    /**
     * Método que retorna os usuários cadastrados no banco
     */
    public static function getUsers(
        string $where = null, 
        string $order = null, 
        string $limit = null, 
        string $fields = '*'
        ): PDOStatement
    {
        return (new Database('user'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método reponsável por retornar um usuário com base em seu e-mail
     */
    public static function getUserByEmail(string $email): User|string
    {
        return (new Database('user'))->select("email = '{$email}'")->fetchObject(self::class);
    }

    /**
     * Método reponsável por retornar um usuário com base no seu ID
     */
    public static function getUserById(int $id): User|string
    {
        return self::getUsers("id = {$id}")->fetchObject(self::class);
    }
}
