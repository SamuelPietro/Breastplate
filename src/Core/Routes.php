<?php

namespace Src\Core;

use Exception;
use Src\Core\Router;

/**
 * The main application class
 */
class Routes
{
    /**
     * The router instance
     *
     * @var Router
     */
    private Router $router;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->defineRoutes();
    }

    /**
     * Defines the routes for the application
     */
    private function defineRoutes(): void
    {
        $this->router->addRoute('GET', '/', 'AppController', 'index');

        $this->router->addRoute('GET', '/users', 'UsersController', 'index');
        $this->router->addRoute('GET', '/users/{id}', 'UsersController', 'show');
        $this->router->addRoute('GET', '/users/create', 'UsersController', 'create');
        $this->router->addRoute('POST', '/users/create', 'UsersController', 'store');
        $this->router->addRoute('GET', '/users/edit/{id}', 'UsersController', 'edit');
        $this->router->addRoute('POST', '/users/edit/{id}', 'UsersController', 'update');
        $this->router->addRoute('GET', '/users/delete/{id}', 'UsersController', 'delete');
    }

    /**
     * Runs the application
     */
    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        try {
            $this->router->route($method, $path);
        } catch (Exception $e) {
            // do nothing
        }
    }
}
