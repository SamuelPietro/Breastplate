<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\View;
use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;
use Src\Core\Csrf;
use Src\Core\WebHelper;
use Src\Database\Connection;


class AuthController
{
    private View $view;
    private UserModel $userModel;
    private WebHelper $webHelper;
    private Csrf $csrf;


    public function __construct()
    {
        $this->view = new View();
        $this->userModel = new UserModel(new Connection());
        $this->webHelper = new WebHelper();
        $this->csrf = new Csrf();
    }

    /**
     * @throws Exception|InvalidArgumentException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function login(): void
    {
        $error = '';
        if ($this->webHelper->isMethod('post')) {
            $this->csrf->verify();

            $email = $this->webHelper->input('email');
            $password = $this->webHelper->input('password');
            $remember = $this->webHelper->input('remember');
            $user = $this->userModel->getByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $this->webHelper->setSession('usr_id', $user['id']);
                $this->webHelper->setSession('usr_name', $user['name']);
                $this->webHelper->setSession('usr_email', $user['email']);
                if (!empty($remember)) {
                    $this->webHelper->setCookie('remember', $user['id'], ['expire' => time() + 7 * 24 * 3600]);
                }
                $this->webHelper->redirect('/');
            } else {
                $error = 'The data provided is invalid. Please check the data and try again.';
            }
        }
        echo $this->view->render('auth/login', ['error' => $error]);
    }

    #[NoReturn] public function logout(): void
    {
        $this->webHelper->removeSession('usr_id');
        $this->webHelper->redirect('/');
    }

    public function isAuthenticated(): bool
    {
        return $this->webHelper->getSession('usr_id') !== null;
    }

    public function getCurrentUserId(): ?int
    {
        return $this->webHelper->getSession('usr_id');
    }
}
