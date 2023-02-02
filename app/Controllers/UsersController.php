<?php

namespace App\Controllers;

use App\Models\UsersModel;

/**
 * Class UsersController
 *
 * @package App\Controllers
 *
 * This class is used to manage user operations such as getting all users, getting a user by id,
 * creating a user, updating a user and deleting a user.
 */
class UsersController
{
    /**
     * @var UsersModel
     */
    private UsersModel $model;

    /**
     * UsersController constructor.
     *
     * Creates a new instance of UsersModel.
     */
    public function __construct()
    {
        $this->model = new UsersModel();
    }

    /**
     * Get all users and include the 'users/index.php' view.
     */
    public function index(): void
    {
        $users = $this->model->getAll();
        include VIEWS_PATH . 'users/index.php';
    }

    /**
     * Get user by id and include the 'users/show.php' view.
     *
     * @param int $id The id of the user to get.
     */
    public function show(int $id): void
    {
        $user = $this->model->getById($id);
        include VIEWS_PATH . 'users/show.php';
    }

    /**
     * Include the 'users/create.php' view.
     */
    public function create(): void
    {
        include VIEWS_PATH . 'users/create.php';
    }

    /**
     * If the request method is POST, create a new user using the data from $_POST.
     * Then redirect to the '/users' page.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create($_POST);
        }
        header("Location: /users");
    }

    /**
     * Get user by id and include the 'users/update.php' view.
     *
     * @param int $id The id of the user to get.
     */
    public function edit(int $id): void
    {
        $user = $this->model->getById($id);
        include VIEWS_PATH . 'users/update.php';
    }

    /**
     * If the request method is POST, update the user with the specified id using the data from $_POST.
     * Then redirect to the '/users' page.
     *
     * @param array $id The id of the user to update.
     */
    public function update(array $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, $_POST);
            header("Location: /users");
        }
    }

    /**
     * Delete the user with the specified id.
     * Then redirect to the '/users' page.
     *
     * @param array $id The id of the user to delete.
     */
    public function delete(array $id): void
    {
        $this->model->delete($id);
        header("Location: /users");
    }
}
