<?php

namespace App\Utils;

use Exception;
use App\Model\Entity\User as EntityUser;

class Examiner
{
    /** 
     * Método responsável por verificar se o id é numerico
    */
    public static function checkId(mixed $id): void
    {
        if (!is_numeric($id)) throw new Exception("O id '{$id}' não é válido.", 400);
    }

    /** 
     * Método responsável por verificar se o array de itens não está vazio
    */
    public static function checkArrayItens(array $itens): void
    {
        if (empty($itens)) throw new Exception('O array de itens está vazio.', 404);
    }

    /**
     * Método responsável por verificar a senha do usuário
     */
    public static function checkUserPassword(string $password_hash, EntityUser $obUser): void
    {
        if (!password_verify($password_hash, $obUser->password_hash)) {
            throw new Exception("O usuário ou senha são inválidos.", 400);
        }
    }

    /** 
     * Método responsável por verificar se o objeto do id informado é uma instancia do objeto requerido
    */
    public static function checkObjectAlReadyExists(
        object|string $object, 
        object|string $instance
        ): void
    {
        if ($object instanceof $instance) throw new Exception('Objeto já existente.', 404);
    }

    /** 
     * Método responsável por verificar se o objeto do valor informado não é uma instancia do objeto requerido
    */
    public static function checkObjectNotExiste(
        object|string $object, 
        object|string $instance
        ): void
    {
        if (!$object instanceof $instance) throw new Exception('Objeto não existente.', 400);
    }

    /** 
     * Método responsável por verificar se o objeto informado foi duplicado
    */
    public static function checkDuplicateObject(
        object|string $object, 
        object|string $instance,
        int $id
        ): void
    {
        if ($object instanceof $instance && $id != $object->id) {
            throw new Exception('Objeto duplicado.', 400);
        }
    }

    /** 
     * Método responsável por verificar se os campos obrigatórios foram preenchidos
    */
    public static function checkRequiredFields(array $fields): void
    {
        // converte (array): [campo, campo2, campo3] 
        // para (string): campo, campo2 e campo3
        $filter =  preg_replace("/(,+\s+\w+)$/", ' e ', implode(", ", array_keys($fields))).array_key_last($fields);

        foreach ($fields as $key => $value) {
            if (!isset($value)) {
                $message = count($fields) > 1 ? "Os campos {$filter} são obrigatórios." : "O campo {$key} é obrigatório";
                throw new Exception($message, 400);
            } else if (empty($value)) {
                $message = count($fields) > 1 ? "Os campos {$filter} não podem estar vazios." : "O campo {$key} não pode estar vazio.";
                throw new Exception($message, 400);
            }
        }
    }
}
