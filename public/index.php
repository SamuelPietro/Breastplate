<?php

declare(strict_types=1);

/**
 * Loads the framework's bootstrap file
 *
 * This code is using the strict type declaration, which means that the type of the parameters and return values are strictly enforced.
 * The `require_once` statement is used to include and run the code from the file `bootstrap.php`, which is located in the `src/Core` directory.
 * The `__DIR__` magic constant returns the directory of the current file, making the path to the `bootstrap.
 * 
**/
require_once __DIR__ . '/../src/Core/bootstrap.php';

/**
 * Sets a content security policy (CSP) and sends it as an HTTP header.
 *
 * @throws Exception if a nonce cannot be generated for use in the content security policy.
 *
 * @return void
 */
function set_csp_header(): void
{
    try {
        $nonce = bin2hex(random_bytes(16));
    } catch (Exception $e) {
        throw new Exception('Unable to generate a nonce for the content security policy.', 0, $e);
    }
    $csp = "default-src 'self'; script-src 'self' 'nonce-$nonce'; img-src *; base-uri 'self'; font-src 'self' data:; style-src 'self' 'unsafe-inline'; object-src 'none';";
    header('Content-Security-Policy: ' . $csp);
}

// Attempts to set the CSP header.
try {
    set_csp_header();
} catch (Exception $e) {
    error_log('Error setting Content-Security-Policy header: ' . $e->getMessage());
}
