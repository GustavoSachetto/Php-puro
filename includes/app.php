<?php

require __DIR__.'/../vendor/autoload.php';

use App\Common\Environment;
use App\Utils\View;
use App\Model\DatabaseManager\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

Environment::load(__DIR__.'/../');

Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'), 
    getenv('DB_PORT')
);

Database::init();

define('URL', getenv('URL'));

View::init([
    'URL' => URL,
]);

MiddlewareQueue::setMap([
    'maintenance'         => \App\Http\Middleware\Maintenance::class,
    'require-admin-login' => \App\Http\Middleware\AccessLevel\User\RequireAdminLogin ::class,
    'require-login'       => \App\Http\Middleware\AccessLevel\User\RequireLogin ::class,
    'require-logout'      => \App\Http\Middleware\AccessLevel\User\RequireLogout::class,
    'jwt-auth'            => \App\Http\Middleware\JWTAuth::class,
    'cache'               => \App\Http\Middleware\Cache::class
]);

MiddlewareQueue::setDefault([
    'maintenance'
]);