<?php

namespace App\Http\Middleware\AccessLevel\User;

use Closure;
use App\Http\Request;
use App\Http\Response;
use App\Http\Middleware\AccessLevel\UserStatus;

class RequireAdminLogin extends UserStatus
{
    /** 
     * Método responsável por verificar se o usuáro está logado como admin, se estiver, ele será redirecionado
    */
    private function userIsLogged(Request $request): void
    {
        $this->isLoggedWithAdmin($request, 'admin', 'login');
    }

    /**
     * Método reponsável por executar o middleware
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->userIsLogged($request);

        return $next($request);
    }
}
