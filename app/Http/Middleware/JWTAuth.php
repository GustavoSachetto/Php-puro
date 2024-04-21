<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Response;
use App\Model\Entity\User;

class JWTAuth
{
    /** 
     * Método responsável por decodificar o Token JWT
    */
    private function getJWTDecode(string $jwt): array
    {
        try {
            return (array)JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));
        } catch (\Exception) {
            throw new Exception("Token inválido", 403);
        }
    }

    /**
     * Método responsável por buscar um usuário no banco de dados
     */
    private function getObjectUser(string $email): User
    {
        return User::getUserByEmail($email);
    }

    /**
     * Método responsável por retornar uma instância de usuário autenticado
     */
    private function getJWTAuthUser(Request $request): User
    {
        $headers = $request->getHeaders();
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';
    
        $decode = $this->getJWTDecode($jwt);
        $obUser = $this->getObjectUser($decode['email'] ?? '');

        return $obUser instanceof User ? $obUser : false;
    }

    /**
     * Método responsável por validar o acesso via JWT
     */
    private function auth(Request $request): bool
    {
        if ($obUser = $this->getJWTAuthUser($request)) {
            $request->setUser($obUser);
            return true;
        }
        throw new Exception("Acesso negado", 403);
    }

    /**
     * Método reponsável por executar o middleware
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->auth($request);
        return $next($request);
    }
}
