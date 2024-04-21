<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Http\Request;
use App\Http\Response;

class Maintenance
{
    /**
     * Método responsável por verificar se a página está em estado de manutenção
     */
    private function checkStatus(): void
    {
        if (getenv('MAINTENANCE') == 'true') {
            throw new Exception("Página em manutenção. Tente novamente mais tarde.", 200);
        }
    }

    /**
     * Método reponsável por executar o middleware
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->checkStatus();
        return $next($request);
    }
}
