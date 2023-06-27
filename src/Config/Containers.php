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
        // Controladores
        $container->bind('AuthController', function ($container) {
            $connection = $container->get('Connection');
            $view = $container->get('View');
            $userModel = $container->get('UserModel');
            $webHelper = $container->get('WebHelper');
            $csrf = $container->get('Csrf');
            return new AuthController($connection, $view, $userModel, $webHelper, $csrf);
        });

        $container->bind('AppController', function ($container) {
            $authController = $container->get('AuthController');
            $webHelper = $container->get('WebHelper');
            $view = $container->get('View');
            return new AppController($authController, $webHelper, $view);
        });

        // Modelos
        $container->bind('UserModel', function ($container) {
            $connection = $container->get('Connection');
            return new UserModel($connection);
        });

        // Core
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
            $csrf = $container->get('Csrf');
            $webHelper = $container->get('WebHelper');
            return new View($csrf, $webHelper);
        });

        $container->bind('Connection', function ($container) {
            return new Connection();
        });

        $container->bind('Csrf', function ($container) {
            return new Csrf();
        });
    }
}
