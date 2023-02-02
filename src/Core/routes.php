<?php

use Src\Core\Router;

/**
 * Instantiates the Router class
 */
$router = new Router();

/**
 * Adds routes to the router
 *
 * The addRoute method accepts three parameters:
 * - The HTTP method (GET, POST, etc)
 * - The URL pattern
 * - The controller and method to handle the route
 */
$router->addRoute('GET', '/', 'AppController', 'index');

$router->addRoute('GET', '/users', 'UsersController', 'index');
$router->addRoute('GET', '/users/{id}', 'UsersController', 'show');
$router->addRoute('GET', '/users/create', 'UsersController', 'create');
$router->addRoute('POST', '/users/create', 'UsersController', 'store');
$router->addRoute('GET', '/users/edit/{id}', 'UsersController', 'edit');
$router->addRoute('POST', '/users/edit/{id}', 'UsersController', 'update');
$router->addRoute('GET', '/users/delete/{id}', 'UsersController', 'delete');

/**
 * Retrieves the current request method and URL
 */
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

/**
 * Tries to match the current request to a route in the router
 *
 * If there's an exception, it is caught and ignored.
 */
try {
    $router->route($method, $path);
} catch (Exception $e) {
}
