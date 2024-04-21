<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use Closure;
use Exception;

class Queue
{
    
    /**
     * Mapeamento de middlewares
     */
    private static array $map = [];

    /**
     * Mapeamento de middlewares que seráo carregados em todas as rotas
     */
    private static array $default = [];

    /**
     * Fila de middlewares a serem executados
     */
    private array $middlewares = [];

    /**
     * Função de execução do controlador
     */
    private Closure $controller;

    /**
     * Argumentos da função do controlador
     */
    private array $controllerArgs = [];

    /**
     * Método responsavel por construir a classe de fila de middlewares
     */
    public function __construct(array $middlewares, Closure $controller, array $controllerArgs)
    {
        $this->middlewares    = array_merge(self::$default, $middlewares);
        $this->controller     = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Método responsável por definir o mapeamento de middlewares
     */
    public static function setMap(array $map): void
    {
        self::$map = $map;
    }

    /**
     * Método responsável por definir o mapeamento de middlewares padrões
     */
    public static function setDefault(array $default): void
    {
        self::$default = $default;
    }

    /**
     * Método responsável por executar o próximo nível da fila de middlewares
     */
    public function next(Request $request): Response
    {
        if (empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);
        $middleware = array_shift($this->middlewares);

        if(!isset(self::$map[$middleware])) {
            throw new Exception("Problemas ao processar o middleware da requisição", 500);
        }
        
        $queue = $this;
        $next  = function ($request) use ($queue) {
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request, $next);
    }
}
