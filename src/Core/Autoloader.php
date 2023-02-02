<?php

namespace Src\Core;

class Autoloader
{
    /**
     * Register the autoloader for the application
     *
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Autoload the required classes
     *
     * @param string $class
     * @return void
     */
    public static function autoload(string $class): void
    {
        $class = str_replace('\\', '/', $class) . '.php';

        if (file_exists($class)) {
            require_once $class;
        }
    }
}

Autoloader::register();
