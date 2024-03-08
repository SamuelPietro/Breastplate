<?php

namespace breastplate\Config;

use DI\Container;
use Exception;
use breastplate\App\Controllers\AppController;
use breastplate\App\Controllers\AuthController;
use breastplate\App\Middlewares\AuthenticationMiddleware;
use breastplate\Src\Core\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
     * The dependency injection container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Constructor.
     *
     * @param Container $container The dependency injection container.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->router = $container->get(Router::class);
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
        $this->router->addRoute('GET', '/', AppController::class, 'index', [AuthenticationMiddleware::class]);

        // Define routes for AuthController.
        $this->router->addGroup('/auth', function () {
            $this->router->addRoute('GET', '/login', AuthController::class, 'login');
            $this->router->addRoute('POST', '/login', AuthController::class, 'login');
            $this->router->addRoute('GET', '/forgot-password', AuthController::class, 'forgotPassword');
            $this->router->addRoute('POST', '/forgot-password', AuthController::class, 'forgotPassword');
            $this->router->addRoute('GET', '/new-password/{token}', AuthController::class, 'newPassword');
            $this->router->addRoute('POST', '/new-password/{token}', AuthController::class, 'newPassword');
            $this->router->addRoute('GET', '/logout', AuthController::class, 'logout');
        });

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
            throw $exception;
        }
    }
}