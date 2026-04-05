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
            $_SESSION['error_message'] = 'An error occurred loading the registration page.';
            header('Location: /');
            exit;
        }
    }

    public function loginForm()
    {
        try {
            require __DIR__ . '/../Views/auth/login.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading the login page.';
            header('Location: /');
            exit;
        }
    }

    public function register()
    {
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
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
            $_SESSION['flash_success'] = 'Welcome! Your account was created successfully. You can now log in with your email and password.';
            $_SESSION['login_prefill_email'] = $email;
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Registration failed: ' . $e->getMessage();
            header('Location: /register');
            exit;
        }
    }

    public function login()
    {
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (trim($email) === '' || trim($password) === '') {
            $_SESSION['error_message'] = 'Please fill in email and password.';
            header('Location: /login');
            exit;
        }

        try {
            $success = $this->authService->login($email, $password);

            if ($success) {
                if (($_SESSION['user_role'] ?? '') === 'admin') {
                    header('Location: /admin');
                } else {
                    header('Location: /');
                }
                exit;
            }

            $_SESSION['error_message'] = 'Login failed (wrong email or password).';
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Login failed: ' . $e->getMessage();
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
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
            $_SESSION['error_message'] = 'Logout failed. Please try again.';
            header('Location: /');
            exit;
        }
    }

}
