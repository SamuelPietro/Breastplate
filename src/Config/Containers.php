<?php

namespace Src\Config;

use App\Controllers\AppController;
use App\Controllers\AuthController;
use App\Models\UserModel;
use Psr\Container\ContainerInterface;
use Src\Core\Bootstrap;
use Src\Core\Csrf;
use Src\Core\Router;
use Src\Core\Routes;
use Src\Core\View;
use Src\Core\WebHelper;
use Src\Database\Connection;
use Src\Exceptions\ErrorHandler;

class Containers
{
    public function __construct(ContainerInterface $container)
    {
        $this->registerControllers($container);
        $this->registerModels($container);
        $this->registerCoreComponents($container);
    }

    private function registerControllers(ContainerInterface $container): void
    {
        $container->bind('AuthController', function ($container) {
            $dependencies = [
                'view' => $container->get('View'),
                'userModel' => $container->get('UserModel'),
                'webHelper' => $container->get('WebHelper'),
                'csrf' => $container->get('Csrf'),
            ];
            return new AuthController(...array_values($dependencies));
        });

        $container->bind('AppController', function ($container) {
            $dependencies = [
                'authController' => $container->get('AuthController'),
                'webHelper' => $container->get('WebHelper'),
                'view' => $container->get('View'),
            ];
            return new AppController(...array_values($dependencies));
        });
    }

    private function registerModels(ContainerInterface $container): void
    {
        $container->bind('UserModel', function ($container) {
            $connection = $container->get('Connection');
            return new UserModel($connection);
        });
    }

    private function registerCoreComponents(ContainerInterface $container): void
    {
        $container->bind('Bootstrap', function ($container) {
            return new Bootstrap($container);
        });

        $container->bind('ErrorHandler', function ($container) {
            return new ErrorHandler();
        });

        $container->bind('WebHelper', function ($container) {
            return new WebHelper();
        });

        $container->bind('Router', function ($container) {
            $errorHandler = $container->get('ErrorHandler');
            return new Router($errorHandler, $container);
        });

        $container->bind('Routes', function ($container) {
            $router = $container->get('Router');
            return new Routes($router);
        });

        $container->bind('View', function ($container) {
            $dependencies = [
                'csrf' => $container->get('Csrf'),
                'webHelper' => $container->get('WebHelper'),
            ];
            return new View(...array_values($dependencies));
        });

        $container->bind('Connection', function ($container) {
            return new Connection();
        });

        $container->bind('Csrf', function ($container) {
            return new Csrf();
        });
    }
}
