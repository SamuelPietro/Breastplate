<?php
declare(strict_types=1);

namespace Src\Core;

use Exception;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class responsible for initializing the application.
 */
class Bootstrap
{
    /**
     * Initializes the application.
     *
     * @throws Exception If an error occurs while starting the application.
     */
    public static function init(): void
    {
        try {

            self::loadDependencies();
            WebHelper::startSession();
            self::loadTranslations();
            self::registerErrorHandler();
            self::defineConstants();
            self::loadRoutes();

        } catch (Exception $e) {
            error_log(gettext('Error initializing the application: ') . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Loads project dependencies through Composer's autoloader and loads environment variables
     * from the .env file.
     */
    private static function loadDependencies(): void
    {
        require_once __DIR__ . '/../../vendor/autoload.php';

        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');
    }

    /**
     * Registers the Whoops error handler.
     */
    private static function registerErrorHandler(): void
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    }

    /**
     * Defines the BASE_URL and VIEWS_PATH constants.
     */
    private static function defineConstants(): void
    {
        define('BASE_URL', getenv('BASE_URL'));
        define("VIEWS_PATH", __DIR__ . '/../../app/Views/');
    }

    /**
     * Loads the routes file.
     * @throws Exception
     */
    private static function loadRoutes(): void
    {
        $routes = new Routes();
        $routes->run();
    }

    private static function loadTranslations(): void
    {
        require_once('../gettext.inc');
    }
}

try {
    Bootstrap::init();
} catch (Exception $e) {
    error_log(gettext('Error executing the application: ') . $e->getMessage());
}
