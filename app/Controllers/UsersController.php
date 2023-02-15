<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\View;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Cache\InvalidArgumentException;
use Src\Core\WebHelper;

/**
 * Class UsersController
 *
 * @package App\Controllers
 *
 * This class is used to manage user operations such as getting all users, getting a user by id,
 * creating a user, updating a user, and deleting a user.
 */
class UsersController
{
    private UserModel $userModel;
    private View $view;
    private AuthController $authController;

    /**
     * UsersController constructor.
     *
     * Creates a new instance of UsersModel and View.
     */
    public function __construct()
    {
        $this->authController = new AuthController();
        if (!$this->authController->isAuthenticated()) {
            WebHelper::redirect('/login');
        }
        $this->view = new View();
        $this->userModel = new UserModel();
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
        $users = $this->userModel->getAll();
        $csrf = (new WebHelper)->getCsrfToken();
        $data = compact('users', 'csrf');
        $templateNames = ['users/index'];
        $this->view->render($templateNames, $data);
    }

    /**
     * Renders the details of a user by id.
     *
     * @param int $id
     * @return void
     * @throws Exception|InvalidArgumentException
     */
    public function show(int $id): void
    {
        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->view->render(['error/404']);
            return;
        }
        $this->view->render(['users/show'], compact('user'));
    }

    /**
     * Renders the form for creating a new user.
     *
     * @return void
     * @throws Exception|InvalidArgumentException
     */
    public function create(): void
    {
        $csrf = (new WebHelper)->getCsrfToken();
        $templateNames = ['users/create'];
        $this->view->render($templateNames, compact('csrf'));
    }

    /**
     * Store a new user.
     *
     * @return void
     * @throws Exception
     */
    #[NoReturn] public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create($_POST);
        }
        WebHelper::redirect('/users');
    }

    /**
     * Renders the form for editing a user.
     *
     * @param int $id
     * @return void
     * @throws Exception|InvalidArgumentException
     */
    public function edit(int $id): void
    {
        $csrf = (new WebHelper)->getCsrfToken();
        $user = $this->userModel->getById($id);
        $data = compact('user', 'csrf');
        $templateNames = ['users/edit'];
        $this->view->render($templateNames, $data);
    }

    /**
     * Updates a user.
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->update($id, $_POST);
            WebHelper::redirect('/users');
        }
    }

    /**
     * Deletes a user.
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    #[NoReturn] public function delete(int $id): void
    {
        $this->userModel->delete($id, $_POST);
        WebHelper::redirect('/users');
    }
}
