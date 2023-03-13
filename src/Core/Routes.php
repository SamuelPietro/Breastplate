<?php

namespace Src\Core;

use Exception;

/**
 * The main application class.
 */
class Routes
{
    /**
     * The router instance.
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
     * Defines the routes for the application.
     *
     * @return void
     */
    private function defineRoutes(): void
    {
        // Define routes for AppController.
        $this->router->addRoute('GET', '/', 'AppController', 'index');

        // Define routes for AuthController.
        $this->router->addRoute('GET', '/login', 'AuthController', 'login');
        $this->router->addRoute('POST', '/login', 'AuthController', 'login');
        $this->router->addRoute('GET', '/logout', 'AuthController', 'logout');


    }

    /**
     * Runs the application.
     *
     * @throws Exception If an error occurs while getting the routes.
     *
     * @return void
     */
    public function run(): void
    {

        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        try {
            $this->router->route($method, $path);
        } catch (Exception $e) {
            error_log(gettext('Error getting routes') . $e->getMessage());
        }
    }
}
