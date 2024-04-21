<?php

namespace App\Http\Middleware\AccessLevel;

use App\Http\Request;
use App\Session\Login\User as SessionLoginUser;

abstract class UserStatus
{
    /** 
     * Método responsável por verificar o status de login do usuário e redirecina-lo conforme o desejado
    */
    private function verifyUserLogged(
        Request $request, 
        bool $logged,
        string $targetRedirect, 
        string $unexpectedRedirect = null
        ): void
    {
        if ($logged) {
            $request->getRouter()->redirect("/{$targetRedirect}");
        } else if ($unexpectedRedirect != null) {
            $request->getRouter()->redirect("/{$unexpectedRedirect}");
        }
    }

    /** 
     * Método responsável por verificar se o usuáro está logado, se estiver, ele será redirecionado
    */
    protected function isLogged(Request $request, string $targetRedirect, string $unexpectedRedirect = null): void
    {
        $this->verifyUserLogged($request, SessionLoginUser::isLogged(), $targetRedirect, $unexpectedRedirect);
    }

    /** 
     * Método responsável por verificar se o usuáro está logado como admin, se estiver, ele será redirecionado
    */
    protected function isLoggedWithAdmin(Request $request, string $targetRedirect, string $unexpectedRedirect = null): void
    {
        $this->verifyUserLogged($request, SessionLoginUser::isLoggedWithAdmin(), $targetRedirect, $unexpectedRedirect);
    }
}
