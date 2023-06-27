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
 * Class AuthController
 *
 * This class is used to manage users and auth operations.
 */
class AuthController
{
    /**
     * @var View The view object.
     */
    private View $view;

    /**
     * @var UserModel The user model object.
     */
    private UserModel $userModel;

    /**
     * @var WebHelper The web helper object.
     */
    private WebHelper $webHelper;

    /**
     * @var Csrf The CSRF object.
     */
    private Csrf $csrf;

    /**
     * @var Connection The database connection object.
     */
    private Connection $connection;

    /**
     * @var string The error message.
     */
    private string $error;

    /**
     * AuthController constructor.
     */
    public function __construct(Connection $connection, View $view, UserModel $userModel, WebHelper $webHelper, Csrf $csrf)
    {
        $this->error = '';
        $this->connection = $connection;
        $this->view = $view;
        $this->userModel = $userModel;
        $this->webHelper = $webHelper;
        $this->csrf = $csrf;
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
     * Renders the login page or handles the login process for POST requests.
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function login(): void
    {
        if ($this->webHelper->isMethod('post')) {
            $this->handlePostRequest();
            return;
        }

        $error = $this->getError();
        echo $this->view->render('auth/login', ['error' => $error]);
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
            return;
        }

        $this->setError('The provided data is invalid. Please check your credentials and try again.');
    }

    /**
     * Handles the "remember me" option and sets the remember cookie if selected.
     *
     * @param array $user The user data.
     */
    private function handleRememberOption(array $user): void
    {
        $remember = $this->webHelper->input('remember', false, FILTER_VALIDATE_BOOLEAN);
        if (!empty($remember)) {
            $expires = time() + 7 * 24 * 3600;
            $this->webHelper->setCookie('remember', $user['id'], ['expires' => $expires]);
        }
    }

    /**
     * Sets the user session after successful login.
     *
     * @param array $user The user data.
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
    public function forgotPassword(): void
    {
        $error = null;

        if ($this->webHelper->isMethod('post')) {
            $email = $this->webHelper->input('email', '', FILTER_VALIDATE_EMAIL);
            $user = $this->userModel->getByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(8)) . time();
                $this->userModel->setToken($user['id'], base64_encode($token));
                $this->webHelper->redirect("/new-password/{$token}");
                return;
            } else {
                $error = 'User not found.';
            }
        }

        echo $this->view->render('auth/forgotPassword', ['error' => $error]);
    }

    /**
     * Handles the password reset process and renders the password newPassword page.
     *
     * @param string $token The reset token.
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
            $this->webHelper->redirect('/forgot-password', ['error' => $this->getError()]);
            return;
        }

        $user = $this->getUserByToken($token);

        if (!$user) {
            $this->setError("Invalid reset link. Please try again!");
            $this->webHelper->redirect('/forgot-password', ['error' => $this->getError()]);
            return;
        }

        if ($this->webHelper->isMethod('post')) {
            $this->processPasswordChange($user);
            return;
        }

        echo $this->view->render('auth/newPassword', ['error' => null]);
    }

    /**
     * Checks if the reset token is expired.
     *
     * @param int $tokenCreationTime The token creation timestamp.
     * @param int $expirationMinutes The token expiration time in minutes.
     *
     * @return bool
     */
    private function isTokenExpired(int $tokenCreationTime, int $expirationMinutes): bool
    {
        return time() > ($tokenCreationTime + ($expirationMinutes * 60));
    }

    /**
     * Handles the case when the reset token is expired.
     *
     */
    #[NoReturn] private function handleExpiredToken(): void
    {
        $this->setError("The time limit to reset your password has expired. Please try again!");
        $this->webHelper->redirect('/forgot-password', ['error' => $this->getError()]);
    }

    /**
     * Retrieves the user based on the reset token.
     *
     * @param string $token The reset token.
     *
     * @throws Exception
     *
     * @return array|null The user data or null if not found.
     */
    private function getUserByToken(string $token): ?array
    {
        return $this->userModel->getByToken(base64_encode($token));
    }

    /**
     * Handles the case when the reset token is invalid.
     *
     */
    #[NoReturn] private function handleInvalidToken(): void
    {
        $this->setError("Invalid reset link. Please try again!");
        $this->webHelper->redirect('/forgot-password', ['error' => $this->getError()]);
    }

    /**
     * Processes the password change request.
     *
     * @param array $user The user data.
     *
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

        echo $this->view->render('auth/newPassword', ['error' => $this->getError()]);
    }

    /**
     * Sets the error message.
     *
     * @param string $errorMessage The error message.
     *
     * @return void
     */
    private function setError(string $errorMessage): void
    {
        $this->error = $errorMessage;
    }

    /**
     * Retrieves the error message.
     *
     * @return string|null The error message or null if no error.
     */
    private function getError(): ?string
    {
        return $this->error;
    }
}
