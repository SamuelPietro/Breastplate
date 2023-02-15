<?php

namespace App\Controllers;

use App\Models\AppModel;
use App\Views\View;
use Exception;
use Psr\Cache\InvalidArgumentException;

/**
 * Class AppController
 *
 * This class is used to manage application operations.
 */
class AppController
{
    private AppModel $model;
    private View $view;

    /**
     * AppController constructor.
     *
     * Initializes the controller with an instance of AppModel and View.
     */
    public function __construct()
    {
        $this->model = new AppModel();
        $this->view = new View();
    }

    /**
     * Renders the application home page.
     *
     * @return void
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function index(): void
    {
        $data = [
            'title' => 'PFrame',
            'content' => '',
        ];
        $templateNames = ['app'];
        $this->view->render($templateNames, $data);
    }
}
