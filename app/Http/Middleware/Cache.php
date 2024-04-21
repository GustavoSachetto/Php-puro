<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Utils\Cache\File as CacheFile;
use Closure;

class Cache
{
    /**
     * Variável que armazena a requisição do atual do usuário
     */
    private Request $request;

    /**
     * Método responsável por validar se o cliente NÃO permite cache por parte do servidor
     */
    private function validateClientCache(): bool
    {
        $headers = $this->request->getHeaders();
        if (isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache') return false;
        
        return true;
    }

    /**
     * Método responsável por validar o tempo de cache no .env
     */
    private function validateCacheTime(): bool
    {   
        if (getenv('CACHE_TIME') <= 0) return false;
        
        return true;
    }

    /**
     * Método responsável por validar o método da requisição
     */
    private function validateMethodGet(): bool
    {
        if ($this->request->getHttpMethod() != 'GET') return false;

        return true;
    }

    /**
     * Método responsável por verificar se a requisição atual pode ser cacheda
     */
    private function isCacheable(): bool
    {
        if (!$this->validateCacheTime() or !$this->validateMethodGet() or !$this->validateClientCache()) return false;
    
        return true;
    }

    /**
     * Método responsável por retornar a hash do cache
     */
    private function getHash(): string
    {
        $uri = $this->request->getRouter()->getUri();
        $queryParams = $this->request->getQueryParams();

        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';
        return rtrim('route-'.preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/')), '-');
    }

    /**
     * Método reponsável por executar o middleware
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->request = $request;
        if (!$this->isCacheable()) return $next($request);

        $hash = $this->getHash();
        return CacheFile::getCache($hash, getenv('CACHE_TIME'), function() use($request, $next) {
            return $next($request);
        });        
    }
}
