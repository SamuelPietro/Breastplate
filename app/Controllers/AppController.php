<?php

namespace App\Controllers;

use App\Models\AppModel;
use App\Views\View;
use Exception;
use Psr\Cache\InvalidArgumentException;

/**
 * Class UsersController
 *
 * @package App\Controllers
 *
 * This class is used to manage user operations such as getting all users, getting a user by id,
 * creating a user, updating a user, and deleting a user.
 */
class AppController
{
    /**
     * @var AppModel
     * Holds an instance of the UsersModel class.
     */
    private AppModel $model;

    /**
     * @var View
     * Holds an instance of the View class.
     */
    private View $view;

    /**
     * UsersController constructor.
     *
     * Creates a new instance of UsersModel and View.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->model = new AppModel();
    }

    /**
     * Renders the list of users.
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
