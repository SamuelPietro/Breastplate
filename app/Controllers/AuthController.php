<?php

namespace App\Controllers;

use App\Models\UserModel;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Cache\InvalidArgumentException;
use Src\Core\Csrf;
use Src\Core\View;
use Src\Core\WebHelper;

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
     * @var string The error message.
     */
    private string $error;

    /**
     * AuthController constructor.
     *
     * @param View $view The view object.
     * @param UserModel $userModel The user model object.
     * @param WebHelper $webHelper The web helper object.
     * @param Csrf $csrf The CSRF object.
     */
    public function __construct(View $view, UserModel $userModel, WebHelper $webHelper, Csrf $csrf)
    {
        $this->error = '';
        $this->view = $view;
        $this->userModel = $userModel;
        $this->webHelper = $webHelper;
        $this->csrf = $csrf;
    }

    /**
     * Checks if the user is authenticated.
     *
     * @return bool Returns true if the user is authenticated, false otherwise.
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
     *
     * @return void
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

        $user = $this->userModel->getByField('email', $email);
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
     * @param array $user The user data.
     *
     * @return void
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
     * @return void
     * @throws Exception
     *
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
            $user = $this->userModel->getByField('email', $email);

            if ($user) {
                $token = bin2hex(random_bytes(8)) . time();
                $this->userModel->setToken($user['id'], base64_encode($token));
                $this->webHelper->redirect("/new-password/$token");
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
        }

        $user = $this->getUserByToken($token);

        if (!$user) {
            $this->setError("Invalid reset link. Please try again!");
            $this->webHelper->redirect('/forgot-password', ['error' => $this->getError()]);
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
     * Retrieves the user based on the reset token.
     *
     * @param string $token The reset token.
     *
     * @return array|null The user data or null if not found.
     * @throws Exception
     *
     */
    private function getUserByToken(string $token): ?array
    {
        return $this->userModel->getByField('token', base64_encode($token));
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
