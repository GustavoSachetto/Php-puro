<?php

use App\Http\Response;
use App\Controller\Api;

// Modelo á ser seguido na definição de rotas da api da aplicação

$obRouter->get('/api/v1/users', [
    'middlewares' => [
        'cache'
    ],
    function ($request) {
        return new Response(200, Api\UserController::get($request), 'application/json');
    }
]);

$obRouter->post('/api/v1/users', [
    function ($request) {
        return new Response(201, Api\UserController::set($request), 'application/json');
    }
]);

$obRouter->options('/api/v1/users', [
    function ($request) {
        return new Response(200, Api\UserController::details($request), 'application/json');
    }
]);

$obRouter->put('/api/v1/users/{id}', [
    function ($request, $id) {
        return new Response(201, Api\UserController::edit($request, $id), 'application/json');
    }
]);

$obRouter->delete('/api/v1/users/{id}', [
    function ($request, $id) {
        return new Response(201, Api\UserController::delete($request, $id), 'application/json');
    }
]);