<?php

namespace Src\Core;

use Exception;

class Csrf
{
    private string $token;

    /**
     * Csrf constructor.
     *
     * @throws Exception
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
     * @return string The generated HTML input field.
     */
    public function generate(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . $this->token . '">';
    }

    /**
     * Verify the submitted CSRF token.
     *
     * @return bool True if the token is valid, false otherwise.
     * @throws Exception
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
