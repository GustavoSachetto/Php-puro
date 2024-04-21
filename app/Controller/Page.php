<?php

namespace App\Controller;

use App\Utils\View;

abstract class Page
{
    /** 
     * Método responsável por renderizar o cabeçalho da página
    */
    private static function getHeader(): string 
    {
        return View::render('layout/header');
    }
    
    /** 
     * Método responsável por renderizar o rodapé da página
    */
    private static function getFooter(): string 
    {
        return View::render('layout/footer');
    }

    /** 
     * Método responsável por renderizar o conteúdo da página
    */
    public static function getPage(string $title, string $content): string 
    {
        return View::render('layout/page', [
            'title' => $title,
            'content' => $content,
            'header' => self::getHeader(),
            'footer' => self::getFooter()
        ]);
    }
}
