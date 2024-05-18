<?php

namespace App\Controller\Pages;

use App\Http\Request;

class Controller
{
    /**
     * Método responsável por retornar o conteúdo
     */
    public static function get(): void
    {
        // código a ser criado
    }
    
    /**
     * Método responsável por pegar um conteúdo específico
     */
    public static function fetch(int|string $id): void
    {
        // código a ser criado
    }

    /**
     * Método responsável por setar um novo conteúdo
     */
    public static function set(Request $request): void
    {
        // código a ser criado
    }   
    
    /**
     * Método responsável por editar um conteúdo
     */
    public static function edit(Request $request, int|string $id): void
    {
        // código a ser criado
    }
    
    /**
     * Método responsável por deletar um conteúdo
     */
    public static function delete(Request $request, int|string $id): void
    {
        // código a ser criado
    }
}
