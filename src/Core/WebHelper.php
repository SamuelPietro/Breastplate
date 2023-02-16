<?php

namespace Src\Core;

use Exception;
use JetBrains\PhpStorm\NoReturn;

class WebHelper
{
    /**
     * Redirect to a given URL
     *
     * @param string $url
     * @return void
     */
    #[NoReturn]
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Get the current URL
     *
     * @return string
     */
    public static function currentUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * Get the value of a given key from $_GET or $_POST
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * Sign in if you haven't already.
     *
     * @return void
     */
    public static function startSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Get the value of a given key from $_SESSION
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function session(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a value for a given key in $_SESSION
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setSession(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }
    /**
     * Returns the value of a key stored in the $_SESSION variable.
     *
     * @param string $key The name of the key to retrieve.
     * @param mixed|null $default The default value to be returned if the key is not found.
     * @return mixed The value of the key in $_SESSION or the default value provided.
     */
    public function getSession(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Remove a value from $_SESSION
     *
     * @param string $key
     * @return void
     */
    public static function removeSession(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get the value of a given key from $_COOKIE
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function cookie(string $key, mixed $default = null): mixed
    {
        return $_COOKIE[$key] ?? $default;
    }

    /**
     * Set a value for a given key in $_COOKIE
     *
     * @param string $key
     * @param mixed $value
     * @param int $expire
     * @param string $domain
     * @return void
     */
    public static function setCookie(string $key, mixed $value, int $expire = 0, string $domain = '/'): void
    {
        setcookie($key, $value, $expire, $domain);
    }

    /**
     * Remove a value from $_COOKIE
     *
     * @param string $key
     * @return void
     */
    public static function removeCookie(string $key): void
    {
        setcookie($key, '', time() - 3600);
    }

    /**
     * Sets a content security policy (CSP) and sends it as an HTTP header.
     *
     * @return void
     * @throws Exception if a nonce cannot be generated for use in the content security policy.
     *
     */
    public static function setCspHeader(): void
    {
        try {
            $nonce = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            throw new Exception('Unable to generate a nonce for the content security policy.', 0, $e);
        }
        $csp = "default-src 'self'; script-src 'self' 'nonce-$nonce'; img-src *; base-uri 'self';
        font-src 'self' data:; style-src 'self' 'unsafe-inline'; object-src 'none';";
        header('Content-Security-Policy: ' . $csp);
    }

    /**
     * Verifies the CSRF token for the current request
     *
     * @param string $csrfToken The CSRF token to be verified
     * @return bool True if the token is valid, false otherwise
     */
    /**
     * Generates a CSRF token and saves it in the session
     *
     * @return string The generated token
     * @throws Exception
     */
    public function getCsrfToken(): string
    {
        // Generate a random token
        $token = bin2hex(random_bytes(32));

        // Save the token in the session
        $_SESSION['csrf_token'] = $token;

        return $token;
    }


    /**
     * Verifies the CSRF token for the current request
     *
     * @param string $csrfToken The CSRF token to be verified
     * @return bool True if the token is valid, false otherwise
     */
    public function verifyCsrfToken(string $csrfToken): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $csrfToken);
    }

    /**
     * Checks if the current request method is the same as the given method.
     *
     * @param string $method The method to check (e.g. 'GET', 'POST', 'PUT', etc.)
     * @return bool Returns `true` if the current request method is the same as the given method, and `false` otherwise.
     */
    public function isMethod(string $method): bool
    {
        return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
    }
}
