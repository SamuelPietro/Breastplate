<?php

use Src\Core\Autoloader;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Loads the project dependencies through Composer's autoloader
 */
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Loads environment variables from the .env file
 */
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

/**
 * Registers Whoops error handler
 *
 * Whoops is a PHP error handler that provides a pretty error page.
 * The error handler is added to the Whoops Run object and registered.
 */
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

/**
 * Creates a new instance of the FilesystemAdapter for caching purposes
 */
$cache = new FilesystemAdapter();

/**
 * Registers the Autoloader class
 *
 * The Autoloader class is responsible for loading the required classes for the application.
 */
$autoloader = new Autoloader();
$autoloader->register();

/**
 * Defines the BASE_URL constant
 *
 * BASE_URL is loaded from the environment variables defined in the .env file.
 */
define('BASE_URL', getenv('BASE_URL'));

/**
 * Defines the VIEWS_PATH constant
 *
 * VIEWS_PATH is a constant that holds the path to the view's directory.
 */
const VIEWS_PATH =  __DIR__ . '/../../app/Views/';

/**
 * Loads the routes file
 */
$rotas = 'routes.php';
require $rotas;

/**
 * Loads the helpers file
 */
require_once 'helpers.php';
