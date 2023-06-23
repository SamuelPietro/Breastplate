<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\View;
use Exception;
use InvalidArgumentException;
use Src\Core\Csrf;
use Src\Core\WebHelper;
use Src\Database\Connection;


class AuthController
{
    private View $view;
    private UserModel $userModel;
    private WebHelper $webHelper;
    private Csrf $csrf;
    private Connection $db;


    public function __construct()
    {
        $this->db = new Connection();
        $this->view = new View();
        $this->userModel = new UserModel($this->db);
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
                    $this->webHelper->setCookie('remember', $user['id'], time() + 7 * 24 * 3600);
                }
                $this->webHelper->redirect('/');
            } else {
                $error = 'The data provided is invalid. Please check the data and try again.';
            }
        }
        $csrfToken = $this->csrf->generate();
        $this->view->render(['auth/login'], ['csrfToken' => $csrfToken, 'error' => $error]);
    }

    public function logout(): void
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
