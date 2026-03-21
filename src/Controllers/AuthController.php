<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function registerForm()
    {
        try {
            require __DIR__ . '/../Views/auth/register.php';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred loading the registration page.';
            header('Location: /');
            exit;
        }
    }

    public function loginForm()
    {
        try {
            require __DIR__ . '/../Views/auth/login.php';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred loading the login page.';
            header('Location: /');
            exit;
        }
    }

    public function register()
    {
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /register');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (trim($name) === '' || trim($email) === '' || trim($password) === '') {
            echo "Please fill all fields.";
            return;
        }

        try {
            $this->authService->register($name, $email, $password);
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Registration failed: ' . $e->getMessage();
            header('Location: /register');
            exit;
        }
    }

    public function login()
    {
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (trim($email) === '' || trim($password) === '') {
            $_SESSION['flash_error'] = 'Please fill in email and password.';
            header('Location: /login');
            exit;
        }

        try {
            $success = $this->authService->login($email, $password);

            if ($success) {
                if (($_SESSION['user_role'] ?? '') === 'admin') {
                    header('Location: /admin');
                } else {
                    header('Location: /courts');
                }
                exit;
            }

            $_SESSION['flash_error'] = 'Login failed (wrong email or password).';
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Login failed: ' . $e->getMessage();
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /');
            exit;
        }

        try {
            // Unset all session variables
            $_SESSION = [];

            // Delete session cookie
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'] ?? '/',
                    $params['domain'] ?? '',
                    $params['secure'] ?? false,
                    $params['httponly'] ?? true
                );
            }

            // Destroy the session
            session_destroy();

            header('Location: /');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Logout failed. Please try again.';
            header('Location: /');
            exit;
        }
    }

}
