<?php

use Src\Core\Autoloader;
use Src\Core\Routes;
use Src\Core\WebHelper;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Initializes the application.
 *
 * @throws Exception If the application fails to initialize.
 */
function init(): void
{
    try {
        // Load the project dependencies through Composer's autoloader
        require_once __DIR__ . '/../../vendor/autoload.php';

        // Load environment variables from the .env file
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');

        // Register Whoops error handler
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();

        // Register the Autoloader class
        $autoloader = new Autoloader();
        $autoloader->register();

        // Define the base URL constant
        define('BASE_URL', getenv('BASE_URL'));

        // Define the views path constant
        define("VIEWS_PATH", __DIR__ . '/../../app/Views/');

        // Load the routes file
        $routes = new Routes();
        $routes->run();

        // Set the Content-Security-Policy header
        WebHelper::set_csp_header();
    } catch (Exception $e) {
        error_log('Error initializing the application: ' . $e->getMessage());
        throw $e;
    }
}

try {
    init();
} catch (Exception $e) {
    error_log('Error executing the application: ' . $e->getMessage());
}
