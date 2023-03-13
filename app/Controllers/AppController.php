<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\View;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Src\Database\Connection;
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
    private Connection $db;

    /**
     * AppController constructor.
     *
     * Initializes the controller with an instance of AppModel and View.
     */
    public function __construct()
    {
        $this->db = new Connection();
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
        $data = [
            'title' => 'PFrame',
            'content' => '',
        ];
        $templateNames = ['app'];
        $this->view->render($templateNames, $data);

    }

    /**
     * @throws Exception|InvalidArgumentException
     */
    public function notFound(): void
    {
        $user = $this->userModel->getById(WebHelper::session('usr_id'));
        $templateName = ['templates/sidebar', 'error/404'];
        $data = compact('user');
        $this->view->render($templateName, $data);

    }

}
