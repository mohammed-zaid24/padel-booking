<?php

namespace App\Repositories;

use PDO;

class Repository
{
    protected function getConnection(): PDO
    {
        $host = 'db';
        $db   = 'padel_booking';
        $user = 'app';
        $pass = 'app';

        return new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    
}
