<?php

namespace Src\Core;

use Exception;

class Csrf
{
    private mixed $token;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $this->token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $this->token;
        } else {
            $this->token = $_SESSION['csrf_token'];
        }
    }

    public function generate(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . $this->token . '">';
    }

    /**
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
