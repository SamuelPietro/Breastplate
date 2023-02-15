<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\View;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Cache\InvalidArgumentException;
use Src\Core\WebHelper;

class AuthController {
    /**
     * @var UserModel
     * Holds an instance of the UsersModel class.
     */
    private UserModel $model;

    /**
     * @var View
     * Holds an instance of the View class.
     */
    private View $view;

    /**
     * AuthController constructor.
     *
     * Creates a new instance of UsersModel and View.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->model = new UserModel();
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function login(): void
    {
        if ((new WebHelper)->isMethod('post')) {
            $email = WebHelper::input('email');
            $password = WebHelper::input('password');

            $user = $this->model->getByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                WebHelper::setSession('user_id', $user['id']);
                WebHelper::redirect('/');
            } else {
                $error = 'Invalid email or password';
            }
        }
        $csrf = (new WebHelper)->getCsrfToken();
        $this->view->render(['auth/login'], ['csrf' => $csrf, 'error' => $error ?? '']);
    }

    #[NoReturn] public function logout(): void
    {
        WebHelper::removeSession('user_id');
        WebHelper::redirect('/');
    }

    public function isAuthenticated(): bool
    {
        return (new WebHelper)->getSession('user_id') !== null;
    }

    public function getCurrentUserId() {
        return (new WebHelper)->getSession('user_id');
    }
}

