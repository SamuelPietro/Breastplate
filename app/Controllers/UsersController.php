<?php

namespace App\Controllers;

use App\Models\UsersModel;

class UsersController
{
    private UsersModel $model;

    public function __construct()
    {
        $this->model = new UsersModel();
    }

    public function index(): void
    {
        $users = $this->model->getAll();
        include VIEWS_PATH . 'users/index.php';
    }

    public function show($id): void
    {
        $user = $this->model->getById($id);
        include VIEWS_PATH . 'users/show.php';
    }

    public function create(): void
    {
        include VIEWS_PATH . 'users/create.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->create($_POST);
        }
        header("Location: /users");
    }

    public function edit($id): void
    {
            $user = $this->model->getById($id);
            include VIEWS_PATH . 'users/update.php';
    }
    public function update($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->update($id, $_POST);
            header("Location: /users");
        } 
    }

    public function delete($id): void
    {
        $this->model->delete($id);
        header("Location: /users");
    }
}
