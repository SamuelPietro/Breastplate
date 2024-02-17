<?php

namespace pFrame\Src\Core;

use Exception;

/**
 * Class responsible for generating and verifying CSRF tokens.
 */
class Csrf
{
    private string $token;

    /**
     * Csrf constructor.
     *
     * Generates a CSRF token if it doesn't already exist in the session.
     *
     * @throws Exception If an error occurs while generating the token.
     */
    public function __construct()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $this->token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $this->token;
        }
        $this->token = $_SESSION['csrf_token'];
    }

    /**
     * Generate a CSRF token input field.
     *
     * Generates an HTML input field with the CSRF token value as a hidden field.
     *
     * @return string The generated HTML input field.
     */
    public function generate(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . $this->token . '">';
    }

    /**
     * Verify the submitted CSRF token.
     *
     * Verifies the submitted CSRF token against the token stored in the session.
     *
     * @return bool True if the token is valid, false otherwise.
     * @throws Exception If an error occurs while verifying the token.
     */
    public function verify(): bool
    {
        $csrfToken = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!isset($csrfToken) || $csrfToken !== $this->token) {
            error_log('Invalid CSRF Token');
            return false;
        }
        return true;
    }
}
