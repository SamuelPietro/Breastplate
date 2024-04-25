<?php

declare(strict_types=1);

namespace Breastplate\Src\Core;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Breastplate\Config\Routes;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class responsible for initializing the application.
 */
class Bootstrap
{
    private Routes $routes;
    private Container $container;
    /**
     * @var mixed|AppConfig
     */
    private mixed $config;

    /**
     * Initializes the application.
     *
     * @param Container $container The dependency injection container.
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->startSession();
        $this->routes = $this->container->get(Routes::class);
        $this->config = $this->container->get(AppConfig::class);

    }

    /**
     * Initializes the application.
     *
     * This method sets up the necessary dependencies, starts the session, and retrieves the routes from the container.
     *
     * @return void
     * @throws Exception
     */
    public function init(): void
    {
        try {
            $this->defineConstants();
            $this->registerErrorHandler();
            $this->loadRoutes();
        } catch (Exception $exception) {
            $this->handleError($exception);
        }
    }

    /**
     * Starts the session if it hasn't already been started.
     *
     * @return void
     */
    private function startSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }


    /**
     * Registers the Whoops error handler.
     *
     * @return void
     */
    private function registerErrorHandler(): void
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    }

    /**
     * Defines the BASE_URL and VIEWS_PATH constants.
     *
     * @return void
     */
    private function defineConstants(): void
    {
        define('BASE_URL', $this->config->get('BASE_URL'));
        define("VIEWS_PATH", __DIR__ . '/../../App/Views/');
    }

    /**
     * Loads the routes file.
     *
     * @throws Exception If an error occurs while loading the routes.
     * @return void
     */
    private function loadRoutes(): void
    {
        $this->routes->run();
    }

    /**
     * Handles the error by logging and re-throwing the exception.
     *
     * @param Exception $exception The exception to handle.
     * @throws Exception The re-thrown exception.
     */
    private function handleError(Exception $exception): void
    {
        error_log('Error initializing the application: ' . $exception->getMessage());
        throw $exception;
    }
}
