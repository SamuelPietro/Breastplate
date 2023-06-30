<?php

namespace App\Controllers;

use Exception;
use Psr\Cache\InvalidArgumentException;
use Src\Core\View;
use Src\Core\WebHelper;

/**
 * Class AppController
 *
 * This class is used to manage application operations.
 */
class AppController
{
    /**
     * @var View The view instance.
     */
    private View $view;

    /**
     * @var AuthController The authentication controller instance.
     */
    private AuthController $authController;

    /**
     * @var WebHelper The web helper instance.
     */
    private WebHelper $webHelper;

    /**
     * AppController constructor.
     *
     * Initializes the controller with an instance of AuthController and WebHelper.
     *
     * @param AuthController $authController The authentication controller.
     * @param WebHelper $webHelper The web helper.
     * @param View $view The view instance.
     */
    public function __construct(AuthController $authController, WebHelper $webHelper, View $view)
    {
        $this->authController = $authController;
        $this->view = $view;
        $this->webHelper = $webHelper;

        if (!$this->authController->isAuthenticated()) {
            $webHelper->redirect('/login');
        }
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
