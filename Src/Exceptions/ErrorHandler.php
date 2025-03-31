<?php

namespace Breastplate\Src\Exceptions;

/**
 * Class ErrorHandler
 *
 * Class responsible for handling different types of errors and returning appropriate responses.
 */
class ErrorHandler
{
    /**
     * Handles the "Not Found" error.
     *
     * @return void The "Not Found" error.
     */
    public function handleNotFound(): void
    {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }

    /**
     * Handles the "Internal Server Error" error.
     *
     * @return void The "Internal Server Error" error.
     */
    public function handleInternalServerError(): void
    {
        header("HTTP/1.0 500 Internal Server Error");
        echo "500 Internal Server Error";
    }
}
