<?php

namespace App\Controllers;

use App\Views\View;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Src\Core\WebHelper;

/**
 * Class AppController
 *
 * This class is used to manage application operations.
 */
class AppController
{
    private View $view;
    private AuthController $authController;

    /**
     * AppController constructor.
     *
     * Initializes the controller with an instance of AppModel and View.
     */
    public function __construct()
    {
        $this->authController = new AuthController();

        if (!$this->authController->isAuthenticated()) {
            WebHelper::redirect('/login');
        }

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
        echo $this->view->render('app');
    }

    /**
     * Renders the not found page.
     *
     * @return void
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function notFound(): void
    {
        echo $this->view->render('error/404');
    }
}
