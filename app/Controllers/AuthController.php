<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\View;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Src\Core\WebHelper;

/**
 * Class AuthController
 *
 * This class is used to manage authentication operations.
 */
class AuthController
{
    private UserModel $model;
    private View $view;

    /**
     * AuthController constructor.
     *
     * Initializes the controller with an instance of UserModel and View.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->model = new UserModel();
    }

    /**
     * Handles the user login.
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function login(): void
    {
        $error = '';
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

        $csrfToken = (new WebHelper)->getCsrfToken();
        $this->view->render(['auth/login'], ['csrfToken' => $csrfToken, 'error' => $error]);
    }

    /**
     * Logs out the user.
     *
     * @return void
     */
    public function logout(): void
    {
        WebHelper::removeSession('user_id');
        WebHelper::redirect('/');
    }

    /**
     * Checks if the user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return (new WebHelper)->getSession('user_id') !== null;
    }

    /**
     * Gets the current user id.
     *
     * @return int|null
     */
    public function getCurrentUserId(): ?int
    {
        return (new WebHelper)->getSession('user_id');
    }
}
