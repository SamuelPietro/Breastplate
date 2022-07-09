<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AppModel;

class AppController
{
    public function __construct()
    {
        $this->model = new AppModel();
    }
    public function index()
    {

        require VIEW . 'theme/header.phtml';
        require VIEW . 'app.phtml';

        require VIEW . 'theme/footer.phtml';
    }
}
