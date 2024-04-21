<?php

use App\Http\Response;
use App\Controller\Pages;

// Modelo á ser seguido na definição de rotas das páginas da aplicação

$obRouter->get('/', [
    'middlewares' => [
        'cache'
    ],
    function ($request) {
        return new Response(200, Pages\HomeController::get($request));
    }
]);

$obRouter->post('/', [
    function ($request) {
        return new Response(200, Pages\HomeController::set($request));
    }
]);

$obRouter->put('/{id}', [
    function ($request, $id) {
        return new Response(200, Pages\HomeController::edit($request, $id));
    }
]);

$obRouter->delete('/{id}', [
    function ($request, $id) {
        return new Response(200, Pages\HomeController::delete($request, $id));
    }
]);