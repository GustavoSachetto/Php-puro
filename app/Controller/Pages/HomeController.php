<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Controller\Page;

class HomeController extends Page
{
    /**
     * Método responsável por pegar o conteúdo da página home
     */
    public static function get(): string
    {
        $title = 'PHP Puro | Bem-vindo ao melhor framework brasileiro de PHP';
        $content = View::render('pages/welcome');
        
        return parent::getPage($title, $content);
    }

    /**
     * Método responsável por setar um novo conteúdo da página home
     */
    public static function set($request): void
    {
        // código a ser criado
    }   
    
    /**
     * Método responsável por editar um conteúdo da página home
     */
    public static function edit($request, $id): void
    {
        // código a ser criado
    }
    
    /**
     * Método responsável por deletar um conteúdo da página home
     */
    public static function delete($request, $id): void
    {
        // código a ser criado
    }
}
