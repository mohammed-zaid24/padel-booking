<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\UserRepository;

class AuthService implements IAuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register(string $name, string $email, string $password): void
    {
        // 1. Hash the password (SECURITY)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 2. Create user model
        $user = new UserModel(
            null,
            $name,
            $email,
            $passwordHash,
            'user'
        );

        // 3. Save user in DB
        $this->userRepository->create($user);
    }

    public function login(string $email, string $password): bool
    {
    $user = $this->userRepository->findByEmail($email);

    if ($user === null) {
        return false;
    }

    // Check password against stored hash
    if (!password_verify($password, $user->passwordHash)) {
        return false;
    }

    // prevent session fixation
    session_regenerate_id(true);

    // Save login info in session
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_role'] = $user->role;

    return true;
}
}
