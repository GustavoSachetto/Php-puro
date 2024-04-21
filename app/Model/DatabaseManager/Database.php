<?php

namespace App\Model\DatabaseManager;

use PDO;
use PDOException;
use PDOStatement;
use App\Model\DatabaseManager\Diagram\Blueprint;

class Database
{
    /**
     * Host de conexão com o banco de dados
     */
    private static string $host;

    /**
     * Nome do banco de dados
     */
    private static string $name;

    /**
     * Usuário do banco
     */
    private static string $user;

    /**
     * Senha de acesso ao banco de dados
     */
    private static string $pass;

    /**
     * Porta de acesso ao banco
     */
    private static int $port;

    /**
     * Nome da tabela a ser manipulada
     */
    private string|null $table;

    /**
     * Nomes das tabelas a serem unidas
     */
    private string|null $join = null;

    /**
     * Instancia de conexão com o banco de dados
     */
    private PDO $connection;

    /**
     * Método responsável por configurar a classe
     */
    public static function config(
        string $host,
        string $name, 
        string $user, 
        string $pass, 
        int $port = 3306
        ): void
    {
        self::$host = $host;
        self::$name = $name;
        self::$user = $user;
        self::$pass = $pass;
        self::$port = $port;
    }

    /**
     * Define a tabela e instancia e conexão
     */
    public function __construct(string $table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Método responsável por criar uma conexão com o mysql
     */
    private static function sqlConnect(): PDO
    {
        try {
            $connect = new PDO('mysql:host='.self::$host.';port='.self::$port,self::$user,self::$pass);
            $connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $connect;
        } catch(PDOException $e){
            die('ERROR: '.$e->getMessage());
        }
    }

    /**
     * Método responsável por criar uma conexão com o banco de dados
     */
    private function setConnection(): void
    {
        try {
            $this->connection = new PDO('mysql:host='.self::$host.';dbname='.self::$name.';port='.self::$port,self::$user,self::$pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            die('ERROR: '.$e->getMessage());
        }
    }

    /**
     * Método responsável por executar queries dentro do banco de dados
     */
    private function execute(string $query, array $params = []): PDOStatement
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch(PDOException $e) {
            die('ERROR: '.$e->getMessage());
        }
    }

    /** 
     * Método reponsável por definir o nosso banco de dados no mysql
    */
    public static function init(): bool
    {
        try {
            self::sqlConnect()->query('CREATE DATABASE IF NOT EXISTS '.self::$name);
        } catch(PDOException $e) {
            die('ERROR: '.$e->getMessage());
        }

        return true;
    }


    /**
     * Método responsável por inserir dados no banco.
     * $values [ field => value ]
     */
    public function insert(array $values): int
    {
        $fields = array_keys($values);
        $binds  = array_pad([],count($fields),'?');
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

        $this->execute($query,array_values($values));

        return $this->connection->lastInsertId();
    }

    /**
     * Método responsável por unir tabelas na consulta no banco
     */
    public function join(string $foreignTable, string $match, string $joinType = "INNER JOIN"): void
    {
        $this->join .= " {$joinType} {$foreignTable} ON {$match} ";
    }

    /**
     * Método responsável por executar uma consulta no banco
     */
    public function select(
        string $where = null, 
        string $order = null, 
        string $limit = null, 
        string $fields = '*'
        ): PDOStatement
    {
        $where = strlen($where) ? 'WHERE '.$where : '';
        $order = strlen($order) ? 'ORDER BY '.$order : '';
        $limit = strlen($limit) ? 'LIMIT '.$limit : '';
        $join  = isset($this->join) ? $join = $this->join : $join = '';

        $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$join.' '.$where.' '.$order.' '.$limit;

        return $this->execute($query);
    }

    /**
     * Método responsável por executar atualizações no banco de dados.
     * $values [ field => value ]
     */
    public function update(string $where, array $values): bool
    {
        $fields = array_keys($values);
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;

        $this->execute($query,array_values($values));

        return true;
    }

    /**
     * Método responsável por excluir dados do banco
     */
    public function delete(string $where): bool
    {
        $query = 'DELETE FROM '.$this->table.' WHERE '.$where;
        $this->execute($query);

        return true;
    }

    /**
     * Método responsável por excluir dados do banco
     */
    public function securityDelete(string $where): bool
    {
        $query = 'UPDATE '.$this->table.' SET deleted = ? WHERE '.$where;
        $this->execute($query, [true]);

        return true;
    }

    /**
     * Método responsável por cadastar tabelas no banco
     */
    public function create(string $tableName, callable $function): bool
    {
        $columns = Blueprint::getColumns($function);
        $query = "CREATE TABLE $tableName ($columns)";
        $this->execute($query);

        return true;
    }

    /**
     * Método responsável por dropar tabelas no banco se elas existirem
     */
    public function dropIfExists(string $tableName): bool
    {
        $query = "DROP TABLE IF EXISTS $tableName";
        $this->execute($query);

        return true;
    }

    /**
     * Método responsável por dropar tabelas no banco
     */
    public function drop(string $tableName): bool
    {
        $query = "DROP TABLE $tableName";
        $this->execute($query);

        return true;
    }
}