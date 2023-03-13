<?php

namespace Src\Core;

use DateTime;
use Exception;

/**
 * WebHelper class.
 *
 * A helper class for web-related functionality.
 */
class WebHelper
{
    /**
     * Redirect to a given URL
     *
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * This function checks if the given URL is valid or not.
     *
     * @param string $url The URL to be checked.
     *
     * @return bool Returns TRUE if the URL is valid and FALSE otherwise.
     */
    public static function isUrlValid(string $url): bool
    {
        $pattern = "/^(?:http(s)?:\/\/)?[a-z0-9]+(?:[.\-][a-z0-9]+)*\.[a-z]{2,6}(?:\/.*)?$/i";
        return preg_match($pattern, $url);
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
        session_destroy();
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
     * Transforms a string to camelCase format.
     *
     * @param string $string  The input string.
     *
     * @return string The input string transformed to camelCase format.
     */
    public function toCamelCase(string $string): string
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    /**
     * Transforms a string to StudlyCaps format.
     *
     * @param string $string  The input string.
     *
     * @return string The input string transformed to StudlyCaps format.
     */
    public function toStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }


    /**
     * Check if a password is strong enough
     *
     * @param string $password The password to check
     *
     * @return bool True if the password is strong enough, false otherwise
     */
    public static function validatePassword(string $password): bool
    {
        // Password must be at least 8 characters long
        if (strlen($password) < 8) {
            return false;
        }

        // Password must contain at least one number, one lowercase letter, one uppercase letter, one special character
        if (!preg_match('/\d/', $password) || !preg_match('/[a-z]/', $password) ||
            !preg_match('/[A-Z]/', $password) || !preg_match('/\W/', $password)) {
            return false;
        }

        return true;
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
     * Formats a date in the specified format.
     *
     * @param string $date The date to be formatted.
     * @param string $format The desired date format.
     * @return string The formatted date.
     * @throws Exception
     */
    public function formatDate(string $date, string $format): string
    {
        $dateTime = new DateTime($date);
        return $dateTime->format($format);
    }

    /**
     * Formats a number with thousands separator and decimal point.
     *
     * @param float $number The number to be formatted.
     * @param int $decimals The number of decimal places.
     * @return string The formatted number.
     */
    public function formatNumber(float $number, int $decimals = 2): string
    {
        return number_format($number, $decimals, ',', '.');
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
