<?php

namespace Src\Core;

use JetBrains\PhpStorm\NoReturn;

/**
 * Redirect to a given URL
 *
 * @param string $url
 * @return void
 */
#[NoReturn] function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}

/**
 * Get the current URL
 *
 * @return string
 */
function currentUrl(): string
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
function input(string $key, mixed $default = null): mixed
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
function session(string $key, mixed $default = null): mixed
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
function setSession(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

/**
 * Remove a value from $_SESSION
 *
 * @param string $key
 * @return void
 */
function removeSession(string $key): void
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
function cookie(string $key, mixed $default = null): mixed
{
    return $_COOKIE[$key] ?? $default;
}

/**
 * Set a value for a given key in $_COOKIE
 *
 * @param string $key
 * @param mixed $value
 * @param int $expire
 * @return void
 */
function setCookie(string $key, mixed $value, int $expire = 0): void
{
    setcookie($key, $value, $expire, '/');
}

/**
 * Remove a value from $_COOKIE
 *
 * @param string $key
 * @return void
 */
function removeCookie(string $key): void
{
    setcookie($key, '', time() - 3600, '/');
}
