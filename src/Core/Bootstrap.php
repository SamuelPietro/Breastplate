<?php
declare(strict_types=1);

namespace Src\Core;

use Exception;
use Psr\Container\ContainerInterface;
use Src\Exceptions\ErrorHandler;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class responsible for initializing the application.
 */
class Bootstrap
{
    private ErrorHandler $errorHandler;
    private Router $router;
    private Routes $routes;
    private ContainerInterface $container;

    /**
     * Initializes the application.
     *
     * @param ContainerInterface $container Container of injection dependency
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->loadDependencies();
        $this->startSession();
        $this->routes = $this->container->get('Routes');
    }

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
     * Sign in if you haven't already.
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
     * Loads project dependencies through Composer's autoloader and loads environment variables
     * from the .env file.
     */
    private function loadDependencies(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');
    }

    /**
     * Registers the Whoops error handler.
     */
    private function registerErrorHandler(): void
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    }

    /**
     * Defines the BASE_URL and VIEWS_PATH constants.
     */
    private function defineConstants(): void
    {
        define('BASE_URL', $_ENV['BASE_URL']);
        define("VIEWS_PATH", __DIR__ . '/../../app/Views/');
    }

    /**
     * Loads the routes file.
     *
     * @throws Exception
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

