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
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (trim($email) === '' || trim($password) === '') {
        echo "Please fill all fields.";
        return;
    }

    $success = $this->authService->login($email, $password);

    if ($success) {
        header('Location: /');
        exit;
    }

    echo "Login failed (wrong email or password).";
  }

    public function logout()
    {
        // Clear session and redirect to home
        session_unset();
        session_destroy();

        header('Location: /');
        exit;
    }

}
