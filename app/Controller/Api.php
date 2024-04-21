<?php

namespace App\Controller;

abstract class Api
{
    /**
     * Método responsável por retornar os detalhes da api
     */
    public static function getDetails()
    {
        return [
            'autor' => 'Gustavo Sachetto',
            'github' => 'https://github.com/GustavoSachetto',
            'email' => 'g.sachettocruz@gmail.com'
        ];
    }
}
