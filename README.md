# Php-puro v1.1
Framework desenvolvido em PHP "puro" prezando a facilidade de trabalhar com a __programação orientada a objetos (POO)__ da versão 8.2.1 do PHP.
Esse framework é constituido na arquitetura __Model View Controller (MVC)__ que promove a facil modularidade, escalabilidade e manutenção de sistemas web.

#framework #php #mvc

## Comandos iniciais:
```
composer install
php cli -d set
php cli -d load
```

<a name="ancora"></a>
## Documentação:
Principais duvidas sobre o framework:

- [Como definir rotas](#rotas)
- [Comandos do terminal](#comandos)
- [Como utilizar Requisições POST ou GET](#request)
- [Como montar um Controller](#controller)
- [Como montar um Model](#model)
- [Como renderizar uma View](#view)
- [Como declarar variáveis no documento html](#view)
- [Como inserir e buscar dados no através do controller](#controller)
  <a id="banco"></a>
- [Como criar tabelas no banco de dados](#banco)
- [Configurar middlewares e outras coisas](#config)

## Banco de dados

### Criação das tabelas
O esquema do seu banco de dados deve ficar na pasta `database/schema`, onde todos os arquivos de tabelas devem estar separados na ordem de criação ASC.

Os __métodos up e down__ são definidos para interação aos comandos do terminal, ou seja, __método up__ serve para subir essa interação ao banco, enquanto o __método down__ para dropar essa interação ao banco. 
Para definir uma nova tabela deve seguir a padronização abaixo do __método up__, onde para setar __novas colunas__ a tabela é preciso apenas atribuir uma nova variável __$table__ chamando um método do tipo de dado desejado.

__Exemplo método up:__
```
public function up(): void
{
    (new Database)->create('nomeDaTabela', function(Blueprint $table) {
        $table->id();
        $table->varchar('nomeDaColuna', 100)->notNull();
    });
}

```

__Exemplo método down:__

```
public function down(): void
{
    (new Database)->dropIfExists('nomeDaTabela');
}

```
### Insersão dos dados

Os arquivos de insersão dos dados devem ficar na pasta `database/information`, onde todos os arquivos de tabelas devem estar separados na ordem de criação ASC.

Assim como na criação das tabelas, também é preciso definir os __métodos up e down__.

__Exemplo método up:__
```
public function up(): void
{
    (new Database('nomeDaTabela'))->insert([
        'nomeDaColuna' => 'valor a ser inserido'
    ]);
}
```

__Exemplo método down:__

```
public function down(): void
{
    (new Database('nomeDaTabela'))->delete('id = 1 ');
}
```

<a id="comandos"></a>

.

## Comandos do Terminal

### Comandos do banco de dados:

| Base        | Comando  | Argumento    | Função                                                                      |
| :---------- | -------- | ------------ | :-------------------------------------------------------------------------- |
| php cli     | __--db__ | __set__      | __Carrega__ as tabelas `database/schema` no banco de dados.                 |
| php cli     | __--db__ | __drop__     | __Dropa__ as tabelas `database/schema` no banco de dados.                   |
| php cli     | __--db__ | __reset__    | __Recarrega__ as tabelas `database/schema` no banco de dados.               |
| php cli     | __--db__ | __load__     | __Insere__ as informações `database/information` para o banco de dados.     |
| php cli     | __--db__ | __fresh__    | __Remove__ as informações `database/information` no banco de dados.         |

### Comandos para criação de arquivos:

| Base        | Comando     | Argumento    | Função                                                                      |
| :---------- | ----------- | ------------ | :-------------------------------------------------------------------------- |
| php cli     | __--build__ | __controller__ | __Cria__ um novo controlador na pasta `app/Controller`.                 |
| php cli     | __--build__ | __model__      | __Cria__ um novo modelo na pasta `app/Model/Entity`.                   |
| php cli     | __--build__ | __table__      | __Cria__ uma nova tabela na pasta `database/schema`. |

__Utilitário:__ para facilitar se você colocar o `argumento:diretorio` será criado um novo arquivo já renomeado.

<a id="rotas"></a>

.

## Rotas

As rotas do framework ficam na pasta `routes` da aplicação. 

* Rotas permitidas: __GET, POST, PUT, DELETE e OPTIONS.__

__Exemplo rota comum GET:__
```
$obRouter->get('/url/exemplo', [
    function($request) {
        return new Response(200, Pages\HomeController::get($request));
    }
]);
```

__Exemplo rota dinamica PUT:__
```
$obRouter->put('/url/exemplo/{id}', [
    function($request, $id) {
        return new Response(200, Pages\HomeController::edit($request, $id));
    }
]);
```

__Exemplo rota com middleware POST:__
```
$obRouter->post('/url/exemplo', [
    'middlewares' => [
        'basic-auth'
    ],
    function($request) {
        return new Response(200, Pages\HomeController::set($request));
    }
]);
```

<a id="request"></a>

.

## Request

As requisições tem por padrão alguns métodos que podem ser acessados pelo controller:
* __getPostVars__ - retorna todas as variáveis __POST__ enviadas pela requisição
* __getQueryParams__ - retorna todas as variáveis __GET__ enviadas pela requisição
* __getUser__ - retorna uma instância de usuário autenticado no site
* __setUser__ - seta uma instância de usuário
* __getHeaders__ - retorna os headers da requisição
* __getUri__ - retorna a URI da requisição
* __getHttpMethod__ - retorna o método HTTP da requisição
* __getRouter__ - retorna a instância de Router

Como acessar esses métodos?

__Exemplo do getPostVars:__

* Através do parametro request passado para o controller é possivel acessar qualquer um dos métodos acima.
  
```
public function metodoExemplo(Request $request): void
{
    $request->getPostVars();
}
```

<a id="model"></a>

.

## Model

Os modelos devem ficar dentro da pasta `app/Model/Entity`.

Toda model tem 4 métodos por padrão:
* __create__ - cadastra os valores no banco 
* __update__ - atualiza os valores no banco
* __delete__ - deleta os valores no banco
* __getNomeDaClasse__ - busca os valores no banco

__Declaracação de uma classe model:__
Toda model deve ser gerada para comportar uma tabela em específico. 

* __Por exemplo__ se eu tenho uma tabela: __"post"__ com as colunas: __"id"__ (identificador da tabela, int unsigned)
__"title"__ (titulo, char 20) e __"content"__ (conteúdo, texto). Deve ser gerado uma model nesse formato:

__Exemplo de Classe:__

```
class Post
{
    public int $id; // coluna id associada no banco
    public string $title; // coluna title associada no banco
    public string $content; // coluna content associada no banco

    public function create(): bool
    {
        $this->id = (new Database('post'))->insert([
            'title'   => $this->title, // referenciando nome da coluna com o valor
            'content' => $this->content 
        ]);
    
        return true;
    }
}
```

__Exemplo método CREATE:__
```
public function create(): bool
{
    $this->id = (new Database('nomeDaTabela'))->insert([
        'nomeDaColuna' => $this->atributoDaClasse
    ]);

    return true;
}
```

__Exemplo método UPDATE:__
```
public function update(): bool
{
    return (new Database('nomeDaTabela'))->update('nomeDaColuna = '.$this->atributoDaClasse, [
        'nomeDaColuna' => $this->atributoDaClasse
    ]);
}
```

__Exemplo método DELETE:__
```
public function delete(): bool
{
    return (new Database('nomeDaTabela'))->securityDelete('nomeDaColuna = '.$this->atributoDaClasse);
}
```

__Exemplo método GET:__
```
public static function getTableName(
    string $where = null,
    string $order = null, 
    string $limit = null, 
    string $fields = '*'
    ): PDOStatement
{
    return (new Database('nomeDaTabela'))->select($where, $order, $limit, $fields);
}
```

<a id="view"></a>

.

## View

A view serve para renderizar variáveis decladas no html a serem substituidas por um conteúdo vindo do banco de dados.

* Para se declarar uma variável no conteúdo html deve seguir este modelo de declaração: __{{nomeDaVariavel}}__

__Exemplo renderizando conteúdo da View:__
```
public static function getPage(): string 
{
    // diretorio da pasta: resources/view
    return View::render('pasta/exemploArquivoHtml', [
        'nomeDaVariavel' => $conteudoAlterado
    ]);
}
```
<a id="controller"></a>

.

## Controller

Os controllers devem ficar dentro da pasta `app/Controller`.

Todo controller tem 5 métodos por padrão:
* __get__ - retorna um valor padrão do controller
* __fetch__ - busca um valor específico do controller por algum ID
* __set__ - cadastra valores no controller
* __edit__ - edita valores no controller
* __delete__ - deleta valores no controller

__Exemplo método get:__
```
public static function get(): array
{   
    $itens = [];
    $results = EntityExemplo::getExemplos(); // model Exemplo

    while($obExemplo = $results->fetchObject(EntityExemplo::class)) {
        $itens[] = [
            'nomeDaColuna' => $obExemplo->atributoDaClasse
        ];
    }

    return $itens;
}
```

__Exemplo método post:__
```
public static function set(Request $request): bool
{   
    $vars = $request->getPostVars();

    $obExemplo = new EntityExemplo;
    $obExemplo->atributoDaClasse = $vars['valorPost'];
    $obExemplo->create();

    return true;
}
```

__Exemplo método delete:__
```
public static function delete(Request $request, int $id): bool
{   
    $vars = $request->getPostVars();

    $obExemplo = EntityExemplo::getExemplos('nomeDaColuna = '.$id); // busca o valor pelo id
    $obExemplo->delete();

    return true;
}
```

<a id="config"></a>

## Configuração

O arquivo de configuração do framework fica na pasta `includes/app.php` da aplicação.

__No arquivo app.php é configurado:__

* Autoload das classes
* Load das Variáveis de ambiente (arquivo .env)
* Configuração do banco de dados
* Auto iniciação do banco de dados
* Define a constante de URL
* Define as variáveis constantes da View
* Define o map dos middlewares das rotas
* Seta os middlewares globais (padrão) de todas as rotas.
  
**********************************************
