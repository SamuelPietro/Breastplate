<?php

namespace Src\Core;

use Exception;

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
        $this->router->addRoute('GET', '/users/show/{id}', 'UsersController', 'show');
        $this->router->addRoute('GET', '/users/create', 'UsersController', 'create');
        $this->router->addRoute('POST', '/users/store', 'UsersController', 'store');
        $this->router->addRoute('GET', '/users/edit/{id}', 'UsersController', 'edit');
        $this->router->addRoute('POST', '/users/update/{id}', 'UsersController', 'update');
        $this->router->addRoute('POST', '/users/delete/{id}', 'UsersController', 'delete');
        $this->router->addRoute('GET', '/login', 'AuthController', 'login');
        $this->router->addRoute('POST', '/login', 'AuthController', 'login');
        $this->router->addRoute('GET', '/logout', 'AuthController', 'logout');
    }

    /**
     * Runs the application
     * @throws Exception
     */
    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        try {
            $this->router->route($method, $path);
        } catch (Exception $e) {
            throw new Exception("Error getting routes: " . $e->getMessage());
        }
    }
}
