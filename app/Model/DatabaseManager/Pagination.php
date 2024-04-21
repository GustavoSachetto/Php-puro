<?php

namespace App\Model\DatabaseManager;

class Pagination
{
    /**
     * Número máximo de registros por página
     */
    private int $limit;
  
    /**
     * Quantidade total de resultados do banco
     */
    private int $results;
  
    /**
     * Quantidade de páginas
     */
    private int $pages;
  
    /**
     * Página atual
     */
    private int $currentPage;
  
    /**
     * Construtor da classe
     */
    public function __construct( 
        int $results, 
        int $currentPage = 1, 
        int $limit = 10
        )
    {
        $this->results     = $results;
        $this->limit       = $limit;
        $this->currentPage = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;
        $this->calculate();
    }
  
    /**
     * Método responsável por calcular a páginação
     */
    private function calculate(): void
    { 
        $this->pages = $this->results > 0 ? ceil($this->results / $this->limit) : 1;
        $this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;
    }
  
    /**
     * Método responsável por retornar a cláusula limit da SQL
     */
    public function getLimit(): string
    {
        $offset = ($this->limit * ($this->currentPage - 1));
        return $offset.','.$this->limit;
    }
  
    /**
     * Método responsável por retornar as opções de páginas disponíveis
     */
    public function getPages(): array
    {
        if($this->pages == 1) return [];
    
        $pages = [];
        for($i = 1; $i <= $this->pages; $i++){
            $pages[] = [
            'page'    => $i,
            'current' => $i == $this->currentPage
            ];
        }
    
        return $pages;
    }
}