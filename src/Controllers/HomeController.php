<?php

namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin') {
            header('Location: /admin');
            exit;
        }

        require __DIR__ . '/../Views/home/index.php';
    }
}
