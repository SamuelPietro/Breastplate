<?php

namespace Src\Core;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
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
     *
     * @param Container $container The dependency injection container.
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->router = $this->container->get(Router::class);
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
        $this->router->addRoute('GET', '/forgot-password', 'AuthController', 'forgotPassword');
        $this->router->addRoute('POST', '/forgot-password', 'AuthController', 'forgotPassword');
        $this->router->addRoute('GET', '/new-password/{token}', 'AuthController', 'newPassword');
        $this->router->addRoute('POST', '/new-password/{token}', 'AuthController', 'newPassword');
    }

    /**
     * Runs the application.
     *
     * @return void
     * @throws Exception If an error occurs while dispatching the routes.
     */
    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        try {
            $this->router->dispatch($method, $path);
        } catch (Exception $exception) {
            error_log('Error dispatching routes: ' . $exception->getMessage());
        }
    }
}
