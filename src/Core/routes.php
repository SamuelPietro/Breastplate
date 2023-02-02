<?php

use Src\Core\Router;

$router = new Router();

$router->addRoute('GET', '/', 'AppController', 'index');

$router->addRoute('GET', '/users', 'UsersController', 'index');
$router->addRoute('GET', '/users/{id}', 'UsersController', 'show');
$router->addRoute('GET', '/users/create', 'UsersController', 'create');
$router->addRoute('POST', '/users/create', 'UsersController', 'store');
$router->addRoute('GET', '/users/edit/{id}', 'UsersController', 'edit');
$router->addRoute('POST', '/users/edit/{id}', 'UsersController', 'update');
$router->addRoute('GET', '/users/delete/{id}', 'UsersController', 'delete');


$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];
try {
    $router->route($method, $path);
} catch (Exception $e) {
}
