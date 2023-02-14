<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Views\View;
use Exception;
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
    /**
     * @var UsersModel
     * Holds an instance of the UsersModel class.
     */
    private UsersModel $model;

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
        $this->model = new UsersModel();
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
        $users = $this->model->getAll();
        $data = ['users' => $users];
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
        $user = $this->model->getById($id);
        $data = ['user' => $user];
        $templateNames = ['users/show'];
        $this->view->render($templateNames, $data);
    }

    /**
     * Renders the form for creating a new user.
     *
     * @return void
     * @throws Exception|InvalidArgumentException
     */
    public function create(): void
    {
        $templateNames = ['users/create'];
        $this->view->render($templateNames, []);
    }

    /**
     * Store a new user.
     *
     * @return void
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create($_POST);
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
        $user = $this->model->getById($id);
        $data = ['user' => $user];
        $templateNames = ['users/update'];
        $this->view->render($templateNames, $data);
    }

    /**
     * Updates a user.
     *
     * @param array $id
     * @return void
     */
    public function update(array $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->edit($id, $_POST);
            WebHelper::redirect('/users');
        }
    }

    /**
     * Deletes a user.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->model->remove($id);
        WebHelper::redirect('/users');
    }
}
