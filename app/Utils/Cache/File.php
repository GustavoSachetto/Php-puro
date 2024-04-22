<?php

namespace App\Utils\Cache;

use Closure;

class File
{
    /**
     * Método responsável por validar o tempo de expiração do conteúdo no cache
     */
    private static function validateCacheExpiration(string $cacheFile, int $expiration): bool
    {
        $createTime = filemtime($cacheFile);
        $diffTime = time() - $createTime;
        
        if ($diffTime > $expiration) return false;

        return true;
    }

    /**
     * Método responsável por retornar o caminho até o arquivo de cache
     */
    private static function getFilePath(string $hash): string 
    {
        $dir = getenv('CACHE_DIR');

        if (!file_exists($dir)) {
            mkdir($dir,0755,true);
        }

        return $dir.'/'.$hash;
    }

    /**
     * Método responsável por retornar o conteúdo gravado no cache
     */
    private static function getContentCache(string $hash, int $expiration): mixed 
    {
        $cacheFile = self::getFilePath($hash);

        if (!file_exists($cacheFile)) return false; 
        if (!self::validateCacheExpiration($cacheFile, $expiration)) return false;

        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);
    }

    /**
     * Método responsável por guardar informações no cache
     */
    private static function storageCache(string $hash, mixed $content):bool
    {
        $serialize = serialize($content);
        $cacheFile = self::getFilePath($hash);

        return file_put_contents($cacheFile,$serialize);
    }

    /**
     * Método responsável por obter uma informação do cache
     */
    public static function getCache(string $hash, int $expiration, Closure $function): mixed
    {
        if ($content = self::getContentCache($hash, $expiration)) {
            return $content;
        } 

        $content = $function();
        self::storageCache($hash, $content);

        return $content;
    }
}
