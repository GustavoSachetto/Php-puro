<?php

namespace App\Http;

use App\Http\Router;
use App\Model\Entity\User;

class Request
{
    /**
     * Instância do Router
     */
    private Router $router;

    /**
     * Método HTTP da requisição
     */
    private string $httpMethod;

    /** 
     * Usuário logado no site
    */
    private User $user;

    /**
     * URL da página
     */
    private string $uri;

    /**
     * Parâmetros da URL ($_GET)
     */
    private array $queryParams = [];

    /**
     * Variáveis recebidas no POST da página ($_POST)
     */
    private array $postVars = [];

    /**
     * Variáveis recebidas no FILES da página ($_FILES)
     */
    private array $fileVars = [];

    /**
     * Cabeçalho da requisição
     */
    private array $headers = [];

    /** 
     * Construtor da classe
     */
    public function __construct(Router $router)
    {
        $this->router      = $router;
        $this->queryParams = $_GET ?? [];
        $this->fileVars    = $_FILES ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();
    }

    /**
     * Método reponsável por definir as variáveis do POST
     */
    private function setPostVars()
    {
        if($this->httpMethod == 'GET') return false;

        $this->postVars = $_POST ?? []; // POST PADRÃO
        
        $inputRaw = file_get_contents('php://input'); // POST JSON
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    /**
     * Método reponsável por definir o usuário
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Método reponsável por pegar o usuário
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Método responsável por definir a URI
     */
    private function setUri(): void
    {
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        $xURI = explode('?', $this->uri);
        $this->uri = $xURI[0];
    }

    /**
     * Método responsável por retornar a instância de Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
    
    /** 
     * Método responsável por retornar o método HTTP da requisição
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /** 
     * Método responsável por retornar a URI da requisição
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /** 
     * Método responsável por retornar os headers da requisição
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /** 
     * Método responsável por retornar os parâmentros da URL da requisição
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /** 
     * Método responsável por retornar as variáveis POST da requisição
     */
    public function getPostVars(): array
    {
        return $this->postVars;
    }

    /** 
     * Método responsável por retornar as variáveis FILES da requisição
     */
    public function getFileVars(): array
    {
        return $this->fileVars;
    }
}