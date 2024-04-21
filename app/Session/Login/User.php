<?php

namespace App\Session\login;

use App\Model\Entity\User as EntityUser;

class User
{   
    /**
     * Método responsável por iniciar a sessão
     */
    private static function init(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Método responsável por criar o login do usuário
     */
    public static function login(EntityUser $obUser): bool
    {
        self::init();

        $_SESSION['user'] = [
            'id'           => $obUser->id,
            'name'         => $obUser->name,
            'email'        => $obUser->email,
            'admin_access' => $obUser->admin_access
        ];

        return true;
    }

    /**
     * Método responsável por verificar se o usuário está logado
     */
    public static function isLogged(): bool
    {
        self::init();

        return isset($_SESSION['user']['id']);
    }

    /**
     * Método responsável por verificar se o usuário está logado
     */
    public static function isLoggedWithAdmin(): bool
    {
        return self::isLogged() ? $_SESSION['user']['admin_access'] : false;
    }

    /**
     * Método responsável por retornar o usuário que está logado
     */
    public static function getLogged(): User
    {
        return self::isLogged() ? $_SESSION['user'] : false;
    }

    /**
     * Método responsavel por executar o logout do usuário
     */
    public static function logout(): bool
    {
        self::init();

        unset($_SESSION['admin']['usuario']);
        return true;
    }
}
