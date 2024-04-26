# Php-puro
Framework desenvolvido em PHP "puro" prezando a facilidade de trabalhar com a __programação orientada a objetos (POO)__ da versão 8.2.1 do PHP.
Esse framework é constituido na arquitetura __Model View Controller (MVC)__ que promove a modularidade, escalabilidade e a manutenção.

#framework #php #mvc

<a name="ancora"></a>
## Documentação:
Principais duvidas sobre o framework:

- [Como definir rotas](#rotas)
- [Comandos do terminal](#comandos)
- [Como montar um Controller](#controller)
  <a id="database"></a>
- [Como criar tabelas no banco de dados](#database)
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
<a id="comandos"></a>
```
    public function down(): void
    {
        (new Database('nomeDaTabela'))->delete('id = 1 ');
    }
```

## Comandos do Terminal

Comandos relacionados ao banco de dados:

| Base        | Comando  | Argumento    | Função                                                                                     |
| :---------- | -------- | ------------ | :----------------------------------------------------------------------------------------- |
| php cli     | __--db__ | __set__      |	__Carrega__ todas as tabelas `database/schema` no banco de dados.                 |
| php cli     | __--db__ | __drop__     | __Dropa__ todas as tabelas `database/schema` no banco de dados.                   |
| php cli     | __--db__ | __reset__    |	__Recarrega__ todas as tabelas `database/schema` no banco de dados.               |
| php cli     | __--db__ | __load__     | __Insere__ todas as informações `database/information` para o banco de dados.     |
| php cli     | __--db__ | __fresh__    |	__Remove__ todas as informações `database/information` no banco de dados.         |

<a id="rotas"></a>

-

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

<a id="controller"></a>

## Controller

Os controllers devem ficar dentro da pasta `app/Controller`.

Todo controller tem 5 métodos por padrão:
* __get__ - retorna um valor padrão do controller
* __fetch__ - busca um valor específico do controller por algum ID
* __set__ - cadastra valores no controller
* __edit__ - edita valores no controller
* __delete__ - deleta valores no controller

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
