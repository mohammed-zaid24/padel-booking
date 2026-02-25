<?php

namespace App\Repositories;

use App\Models\UserModel;

interface IUserRepository
{
    public function create(UserModel $user): void;
    public function findByEmail(string $email): ?UserModel;
}
