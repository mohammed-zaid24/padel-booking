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
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function loginForm()
    {
        require __DIR__ . '/../Views/auth/login.php';
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

        $this->authService->register($name, $email, $password);

        header('Location: /login');
        exit;
    
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
        echo "Please fill all fields.";
        return;
    }

    $success = $this->authService->login($email, $password);

    if ($success) {
        // Redirect based on role
        if (($_SESSION['user_role'] ?? '') === 'admin') {
            header('Location: /admin');
        } else {
            header('Location: /courts');
        }
        exit;
    }

    echo "Login failed (wrong email or password).";
  }

    public function logout()
    {
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /');
            exit;
        }

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
    }

}
