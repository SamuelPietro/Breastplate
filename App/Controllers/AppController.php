<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AppModel;

class AppController
{
    private AppModel $model;

    public function __construct()
    {
        $this->model = new AppModel();
    }
    public function index(): void
    {

        require VIEW . 'theme/header.php';
        require VIEW . 'app.php';

        require VIEW . 'theme/footer.php';
    }
}
