<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;
use App\Http\Middleware\Queue as MiddlewareQueue;

class Router
{
    /**
     * URL completa do projeto (raiz)
     */
    private string $url= '';

    /**
     * Prefixo de todas as rotas
     */
    private string $prefix = '';

    /**
     * Índice de rotas
     */
    private array $routes = [];

    /**
     * Instância de request
     */
    private Request $request;

    /**
     * Content type padrão do response
     */
    private string $contentType = 'text/html';

    /**
     * Método responsável por iniciar a classe
     */
    public function __construct(string $url)
    {
        $this->request  = new Request($this);
        $this->url      = $url;   
        $this->setPrefix();
    }

    /**
     * Método responsável por alterar o valor do content type
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix(): void
    {
        $parseUrl = parse_url($this->url);

        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     */
    private function addRoute(string $method, string $route, array $params = []): void
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
            }
        }

        $params['middlewares'] = $params['middlewares'] ?? [];
        $params['variables'] = [];
        
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }
        $route = rtrim($route,'/');
        $patternRoute = '/^'. str_replace('/', '\/', $route) . '$/';

        $this->routes[$patternRoute][$method] = $params;

    }

    /**
     * Método responsável por definir uma rota de GET
     */
    public function get(string $route, array $params = []): void
    {
        $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     */
    public function post(string $route, array $params = []): void
    {
        $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     */
    public function put(string $route, array $params = []): void
    {
        $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     */
    public function delete(string $route, array $params = []): void
    {
        $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de OPTIONS
     */
    public function options(string $route, array $params = []): void
    {
        $this->addRoute('OPTIONS', $route, $params);
    }

    
    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     */
    public function getUri(): string
    {
        $uri = $this->request->getUri();
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        return rtrim(end($xUri), '/');
    }

    /**
     * Método responsável por retornar os dados da rota atual
     */
    private function getRoute(): array
    {
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $patternRoute => $methods) {
            if (preg_match($patternRoute, $uri, $matches)) {
                if (isset($methods[$httpMethod])) {
                    unset($matches[0]);

                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                   
                    return $methods[$httpMethod];
                }
                throw new Exception("Método não permitido", 405);
            }
        }
        throw new Exception("Url não encontrada", 404);
    }

    /**
     * Método responsável por executar a rota atual
     */
    public function run(): Response
    {
        try {
            $route = $this->getRoute();

            if (!isset($route['controller'])) {
                throw new Exception("A Url não pode ser processada", 500);
            }

            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return (new MiddlewareQueue($route['middlewares'],$route['controller'], $args))->next($this->request);
            
        } catch (Exception $e) {
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }

    /**
     * Método responsável por retornar a mensagem de erro de acordo com o content type
     */
    private function getErrorMessage(string $message): mixed
    {
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $message
                ];
                break;
            default:
                return $message;
                break;
        }
    }

    /**
     * Método responsavel por retornar a URL atual
     */
    public function getCurrentUrl(): string
    {
        return $this->url.$this->getUri();
    }

    /**
     * Método responsável por redirecionar a URL
     */
    public function redirect(string $route): void
    {
        $url = $this->url.$route;

        header('location: '.$url);
        exit;
    }
}
