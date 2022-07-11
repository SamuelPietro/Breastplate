<?php

declare(strict_types=1);

namespace Core;

class ErrorController
{
    public function index($type, $return): void
    {
        // load views
        require VIEW . 'theme/header.php';
        require VIEW . 'error/index.php';
        require VIEW . 'theme/footer.php';
    }
}
