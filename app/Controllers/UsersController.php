<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Views\View;
use Exception;

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
     */
    public function index(): void
    {
        $users = $this->model->getAll();
        $this->view->render('users/index', ['users' => $users]);
    }

    /**
     * Renders the details of a user by id.
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function show(int $id): void
    {
        $user = $this->model->getById($id);
        $this->view->render('users/show', ['user' => $user]);
    }

    /**
     * Renders the form for creating a new user.
     *
     * @return void
     * @throws Exception
     */
    public function create(): void
    {
        $this->view->render('users/create');
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
        header("Location: /users");
    }

    /**
     * Renders the form for editing a user.
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function edit(int $id): void
    {
        $user = $this->model->getById($id);
        $this->view->render('users/update', ['user' => $user]);
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
            header("Location: /users");
        }
    }

    /**
     * Deletes a user.
     *
     * @param array $id
     * @return void
     */
    public function delete($id): void
    {
        $this->model->remove($id);
        header("Location: /users");
    }
}
