<?php

declare(strict_types=1);

namespace Core;

class ErrorController
{
    public function index($type, $return)
    {
        // load views
        require VIEW . 'theme/header.phtml';
        require VIEW . 'error/index.phtml';
        require VIEW . 'theme/footer.phtml';
    }
}
