<?php

namespace Src\Exceptions;

class ErrorHandler
{
    public function handleNotFound(): void
    {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }

    public function handleInternalServerError(): void
    {
        header("HTTP/1.0 500 Internal Server Error");
        echo "500 Internal Server Error";
    }
}