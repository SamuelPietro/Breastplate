<?php

declare(strict_types=1);

/**
 * Loads the framework's bootstrap file.
 *
 * This code is using the strict type declaration, which means that
 * the type of the parameters and return values are strictly enforced.
 * The `require_once` statement is used to include and run the code
 * from the file `Bootstrap.php`, which is located in the `src/Core` directory.
 * The `__DIR__` magic constant returns the directory of the current file, making the path to the `bootstrap`.
 *
 * @throws Exception If an error occurs during the application execution.
 **/

use DI\Container;
use Src\Core\Bootstrap;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $container = createContainer();
    $bootstrap = createBootstrap($container);
    $bootstrap->init();
} catch (Exception $exception) {
    error_log('Error executing the application: ' . $exception->getMessage());
}

/**
 * Creates a new instance of the dependency injection container.
 *
 * @return Container The dependency injection container.
 * @throws Exception If an error occurs while creating the container.
 */
function createContainer(): Container
{
    return new Container();
}

/**
 * Creates a new instance of the Bootstrap class.
 *
 * @param Container $container The dependency injection container.
 * @return Bootstrap The Bootstrap class instance.
 * @throws Exception If an error occurs while creating the Bootstrap instance.
 */
function createBootstrap(Container $container): Bootstrap
{
    return new Bootstrap($container);
}
