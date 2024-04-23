<?php

namespace App\Model\DatabaseManager\Diagram;

class Key
{
    /** 
     * Armazena atributos da coluna gerada pela Blueprint
    */
    public string $attributes;

    /** 
     * Seta os novos atributos da coluna.
    */
    public function __construct(string $attributes) 
    {
        $this->attributes = $attributes;
    }

    /** 
     * Método reponsável por gerar uma chave unique
    */
    public function unique(): Key
    {
        $this->attributes .= " unique";
        return $this;
    }

    /** 
     * Método reponsável por gerar uma chave primary key
    */
    public function primary(): Key
    {
        $this->attributes .= " primary key";
        return $this;
    }
    
    /** 
     * Método reponsável por gerar uma chave not null
    */
    public function notNull(): Key
    {
        $this->attributes .= " not null";
        return $this;
    }

    /** 
     * Método reponsável por gerar uma chave default
    */
    public function default(string $attributes): Key
    {
        $this->attributes .= " default {$attributes}";
        return $this;
    }
}
