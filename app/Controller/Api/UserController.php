<?php

namespace App\Controller\Api;

use App\Controller\Api;
use App\Model\Entity\User as EntityUser;

class UserController extends Api
{
    /**
     * Método responsável por retornar os usuários existentes
     */
    public static function get(): array
    {   
        $results = EntityUser::getUsers(null, 'id ASC');

        while($obUser = $results->fetchObject(EntityUser::class)) {
            $itens[] = [
                'id' => $obUser->id,
                'name' => $obUser->name,
                'email' => $obUser->email,
                'admin_access' => $obUser->admin_access
            ];
        }

        return $itens;
    }

    /**
     * Método responsável por setar um novo usuário
     */
    public static function set($request): void
    {
        // código a ser criado
    }   
    
    /**
     * Método responsável por editar um usuário pelo seu id
     */
    public static function edit($request, $id): void
    {
        // código a ser criado
    }
    
    /**
     * Método responsável por deletar um usuário pelo seu id
     */
    public static function delete($request, $id): void
    {
        // código a ser criado
    }

    /**
     * Método responsável por retornar os detalhes da api
     */
    public static function details($request): array
    {
        return parent::getDetails();
    }
}
