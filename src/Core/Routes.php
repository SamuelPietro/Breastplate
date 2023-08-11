<?php

namespace Src\Core;

use App\Controllers\AppController;
use App\Controllers\AuthController;
use DI\Container;
use Exception;
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
        $this->router->addRoute('GET', '/', AppController::class, 'index');
        $this->router->addRoute('POST', '/deploy', AutomaticDeployment::class, 'deploy');
        $this->router->addRoute('GET', '/suporte', AppController::class, 'index');

        // Define routes for AuthController.
        $this->router->addRoute('GET', '/login', AuthController::class, 'login');
        $this->router->addRoute('POST', '/login', AuthController::class, 'login');
        $this->router->addRoute('GET', '/recuperar-senha', AuthController::class, 'forgotPassword');
        $this->router->addRoute('POST', '/recuperar-senha', AuthController::class, 'forgotPassword');
        $this->router->addRoute('GET', '/nova-senha/{token}', AuthController::class, 'newPassword');
        $this->router->addRoute('POST', '/nova-senha/{token}', AuthController::class, 'newPassword');
        $this->router->addRoute('GET', '/logout', AuthController::class, 'logout');

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