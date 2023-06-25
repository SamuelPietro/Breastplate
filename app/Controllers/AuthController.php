<?php

namespace App\Controllers;

use App\Models\UserModel;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Cache\InvalidArgumentException;
use Src\Core\Csrf;
use Src\Core\View;
use Src\Core\WebHelper;
use Src\Database\Connection;

/**
 * Class AppController
 *
 * This class is used to manage users and auth operations.
 */
class AuthController
{
    /**
     * @var View
     */
    private View $view;
    /**
     * @var UserModel
     */
    private UserModel $userModel;
    /**
     * @var WebHelper
     */
    private WebHelper $webHelper;
    /**
     * @var Csrf
     */
    private Csrf $csrf;
    /**
     * @var Connection
     */
    private Connection $connection;
    /**
     * @var string
     */
    private string $error;

    /**
     * Construtor of AuthController
     */
    public function __construct()
    {
        $this->error = '';
        $this->connection = new Connection();
        $this->view = new View();
        $this->userModel = new UserModel($this->connection);
        $this->webHelper = new WebHelper();
        $this->csrf = new Csrf();
    }

    /**
     * Renders the login page or handles the login process for POST requests.
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function login(): void
    {
        if ($this->webHelper->isMethod('post')) {
            $this->handlePostRequest();
        }

        $error = $this->getError();
        echo $this->view->render('auth/login', ['error' => $error]);
    }

    /**
     * Handles the login process for POST requests.
     *
     * @throws Exception
     */
    private function handlePostRequest(): void
    {
        if (!$this->csrf->verify()) {
            $this->setError('Invalid request parameters.');
            return;
        }

        $email = $this->webHelper->input('email', '', FILTER_VALIDATE_EMAIL);
        $password = $this->webHelper->input('password');

        if (!WebHelper::validatePassword($password)) {
            $this->setError('The provided data does not meet the minimum security requirements.');
            return;
        }

        $user = $this->userModel->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $this->setSession($user);
            $this->handleRememberOption($user);
            $this->webHelper->redirect('/');
        }

        $this->setError('The provided data is invalid. Please check your credentials and try again.');
    }

    /**
     * Handles the "remember me" option and sets the remember cookie if selected.
     *
     * @param array $user
     */
    private function handleRememberOption(array $user): void
    {
        $remember = $this->webHelper->input('remember', false, FILTER_VALIDATE_BOOLEAN);
        if (!empty($remember)) {
            $this->webHelper->setCookie('remember', $user['id'], ['expire' => time() + 7 * 24 * 3600]);
        }
    }

    /**
     * Sets the user session after successful login.
     *
     * @param array $user
     *
     * @throws Exception
     */
    private function setSession(array $user): void
    {
        $this->webHelper->setSession('usr_id', $user['id']);
        $this->webHelper->setSession('usr_name', $user['name']);
    }

    /**
     * Renders the password reset page or handles the password reset process for POST requests.
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function reset(): void
    {
        $error = null;

        if ($this->webHelper->isMethod('post')) {
            $email = $this->webHelper->input('email', '', FILTER_VALIDATE_EMAIL);
            $user = $this->userModel->getByEmail($email);

            if ($user) {
                $token = base64_encode(bin2hex(random_bytes(8)) . time());
                $this->userModel->setToken($user['id'], $token);
                echo $this->view->render('auth/reset', ['error' => $error]);
            } else {
                $error = 'User not found.';
                echo $this->view->render('auth/forgotPassword', ['error' => $error]);
            }
        }
    }

    /**
     * Handles the password reset process and renders the password reset page.
     *
     * @param string $token
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function newPassword(string $token): void
    {
        $tokenCreationTime = hexdec(substr($token, -10));
        $expirationMinutes = 15;

        if ($this->isTokenExpired($tokenCreationTime, $expirationMinutes)) {
            $this->setError("The time limit to reset your password has expired. Please try again!");
            $this->webHelper->redirect('/forgotPassword', ['error' => $this->getError()]);
        }

        $user = $this->getUserByToken($token);

        if (!$user) {
            $this->setError("Invalid reset link. Please try again!");
            $this->webHelper->redirect('/forgotPassword', ['error' => $this->getError()]);
        }

        if ($this->webHelper->isMethod('post')) {
            $this->processPasswordChange($user);
            return;
        }

        echo $this->view->render('auth/reset', ['error' => null]);
    }

    private function isTokenExpired(int $tokenCreationTime, int $expirationMinutes): bool
    {
        return time() > ($tokenCreationTime + ($expirationMinutes * 60));
    }

    #[NoReturn]
    private function handleExpiredToken(): void
    {
        $this->setError("The time limit to reset your password has expired. Please try again!");
        $this->webHelper->redirect('/forgotPassword', ['error' => $this->getError()]);
    }

    /**
     * @throws Exception
     */
    private function getUserByToken(string $token): ?array
    {
        return $this->userModel->getByToken(base64_encode($token));
    }

    #[NoReturn]
    private function handleInvalidToken(): void
    {
        $this->setError("Invalid reset link. Please try again!");
        $this->webHelper->redirect('/forgotPassword', ['error' => $this->getError()]);
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    private function processPasswordChange(array $user): void
    {
        $password = $this->webHelper->input('password');

        if (!WebHelper::validatePassword($password)) {
            $this->setError('The provided data does not meet the minimum security requirements.');
        } elseif ($this->userModel->changePassword($user['id'], $password)) {
            $this->userModel->setToken($user['id'], '');
            $success = "New password set successfully. Proceed to login!";
            $this->webHelper->redirect('/login', ['success' => $success]);
        } else {
            $this->setError("Something went wrong while setting your new password. Please try again!");
        }

        echo $this->view->render('auth/reset', ['error' => $this->getError()]);
    }

    private function setError(string $errorMessage): void
    {
        $this->error = $errorMessage;
    }

    private function getError(): ?string
    {
        return $this->error;
    }


    /**
     * Logs out the user by removing the session and redirecting to the login page.
     */
    #[NoReturn] public function logout(): void
    {
        $this->webHelper->removeSession('usr_id');
        $this->webHelper->removeSession('usr_name');
        $this->webHelper->destroySession();
        $this->webHelper->redirect('/login');
    }

    /**
     * Checks if the user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->webHelper->getSession('usr_id') !== null;
    }

    /**
     * Retrieves the current user's ID.
     *
     * @return int|null
     */
    public function getCurrentUserId(): ?int
    {
        return $this->webHelper->getSession('usr_id');
    }
}
