<?php

namespace Breastplate\Src\Core;

use DateTime;
use Exception;
use JetBrains\PhpStorm\NoReturn;

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
     * @param array $data
     * @return void
     */
    #[NoReturn] public static function redirect(string $url, array $data = []): void
    {
        self::setSession('redirect_data', $data, 3);
        header("Location: $url");
        exit;
    }

    /**
     * Set a value for a given key in $_SESSION
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $expirationTime
     * @return void
     */
    public static function setSession(string $key, mixed $value, int $expirationTime = null): void
    {
        if ($expirationTime) {
            $_SESSION[$key] = [
                'value' => $value,
                'expiration_time' => time() + $expirationTime
            ];
        } else {
            $_SESSION[$key] = $value;
        }
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
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Get the value of a given key from $_GET or $_POST
     *
     * @param string $key
     * @param mixed|null $default
     * @param int $filter
     * @return mixed
     */
    public static function input(string $key, mixed $default = null, int $filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS): mixed
    {
        $value = $_REQUEST[$key] ?? $default;
        return filter_var($value, $filter);
    }

    /**
     * Returns the value of a key stored in the $_SESSION variable.
     *
     * @param string $key The name of the key to retrieve.
     * @param mixed|null $default The default value to be returned if the key is not found.
     * @return mixed The value of the key in $_SESSION or the default value provided.
     */
    public static function getSession(string $key, mixed $default = null): mixed
    {
        if (isset($_SESSION[$key]['expiration_time']) && time() > $_SESSION[$key]['expiration_time']) {
            unset($_SESSION[$key]);
        }

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

        if (empty($_SESSION)) {
            session_destroy();
        }
    }

    /**
     * Get the value of a given key from $_COOKIE
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function getCookie(string $key, mixed $default = null): mixed
    {
        return filter_input(INPUT_COOKIE, $key, FILTER_SANITIZE_SPECIAL_CHARS) ?? $default;
    }

    /**
     * Set a value for a given key in $_COOKIE
     *
     * @param string $key
     * @param mixed $value
     * @param array $options
     * @return void
     */
    public static function setCookie(string $key, mixed $value, array $options = []): void
    {
        $defaultOptions = [
            'expires' => 0,
            'domain' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Strict'
        ];

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $defaultOptions['secure'] = true;
        }

        $options = array_merge($defaultOptions, $options);

        setcookie($key, $value, $options);
    }

    /**
     * Remove a value from $_COOKIE
     *
     * @param string $key
     * @param bool $secure
     * @return void
     */
    public static function removeCookie(string $key, bool $secure = false): void
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $secure = true;
        }

        setcookie($key, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
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
            !preg_match('/[A-Z]/', $password) || !preg_match('/[\W_]/', $password)) {
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
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        return $protocol . '://' . $host . $uri;
    }

    /**
     * Formats a date in the specified format.
     *
     * @param string $date The date to be formatted.
     * @param string $format The desired date format.
     * @return string The formatted date.
     * @throws Exception
     */
    public static function formatDate(string $date, string $format): string
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
    public static function formatNumber(float $number, int $decimals = 2): string
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Checks if the current request method is the same as the given method.
     *
     * @param string $method The method to check (e.g. 'GET', 'POST', 'PUT', etc.)
     * @return bool Returns `true` if the current request method is the same as the given method, and `false` otherwise.
     */
    public static function isMethod(string $method): bool
    {
        return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
    }

    /**
     * Destroys the current session.
     */
    public function destroySession(): void
    {
        session_destroy();
    }
}
