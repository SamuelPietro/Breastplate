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
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
    public static function set_csp_header(): void
    {
        try {
            $nonce = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            throw new Exception('Unable to generate a nonce for the content security policy.', 0, $e);
        }
        $csp = "default-src 'self'; script-src 'self' 'nonce-$nonce'; img-src *; base-uri 'self'; font-src 'self' data:; style-src 'self' 'unsafe-inline'; object-src 'none';";
        header('Content-Security-Policy: ' . $csp);
    }
}
