<?php

namespace App\Repositories;

use App\Models\UserModel;
use PDO;

class UserRepository extends Repository implements IUserRepository
{
    public function create(UserModel $user): void
    {
        $pdo = $this->getConnection();

        $sql = "
            INSERT INTO users (name, email, password_hash, role)
            VALUES (:name, :email, :password_hash, :role)
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'name' => $user->name,
            'email' => $user->email,
            'password_hash' => $user->passwordHash,
            'role' => $user->role
        ]);
    }

    public function findByEmail(string $email): ?UserModel
   {
    $pdo = $this->getConnection();

    $sql = "SELECT id, name, email, password_hash, role FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    }

    return new UserModel(
        (int)$row['id'],
        $row['name'],
        $row['email'],
        $row['password_hash'],
        $row['role']
    );
}
}
